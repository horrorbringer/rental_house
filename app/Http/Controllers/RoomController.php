<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Building;
use App\Models\Invoice;
use App\Models\Rental;
use App\Models\Room;
use App\Models\UtilityUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::with(['building', 'rentals.tenant'])
            ->whereHas('building', function ($query) {
                $query->where('user_id', Auth::id());
            });

        if ($request->has('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Eager load active rentals count for capacity check
        $query->withCount('activeRentals');

        $rooms = $query->latest()->paginate(12);
        $buildings = Building::where('user_id', Auth::id())->get();

        return view('admin.rooms.index', compact('rooms', 'buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buildings = Building::where('user_id', Auth::id())->get();
        return view('admin.rooms.create', compact('buildings'));
    }

    public function quickAddRental(Room $room)
    {
        $notInitializeUtility = $room->activeRentals()->count() === 0;

        return view('admin.rooms.quick-add-rental', compact('room', 'notInitializeUtility'));
    }

    public function quickUtility(Rental $rental)
    {
        $initializeUtility = $rental->room->activeRentals()->count() > 0;

        return view('admin.rooms.quick-add-utility', compact('rental', 'initializeUtility'));
    }

    public function storeQuickUtility(Request $request, Rental $rental)
{
    $messages = [
        'water_usage.required'    => 'Please enter the current water meter reading',
        'water_usage.numeric'     => 'Water meter reading must be a number',
        'water_usage.min'         => 'Water meter reading cannot be negative',
        'electric_usage.required' => 'Please enter the current electric meter reading',
        'electric_usage.numeric'  => 'Electric meter reading must be a number',
        'electric_usage.min'      => 'Electric meter reading cannot be negative',
        'reading_date.required'   => 'Please select a reading date',
        'reading_date.date'       => 'Invalid reading date format',
        'notes.max'               => 'Notes cannot exceed 1000 characters',
    ];

    $validated = $request->validate([
        'water_usage'   => ['required', 'numeric', 'min:0'],
        'electric_usage'=> ['required', 'numeric', 'min:0'],
        'notes'         => ['nullable', 'string', 'max:1000'],
        'reading_date'  => [
            'required', 'date',
            function ($attribute, $value, $fail) use ($rental) {
                $readingDate = Carbon::make($value);
                if (!$readingDate) {
                    return $fail('Invalid reading date.');
                }

                $latestReading = $rental->utilityUsages()
                    ->orderBy('reading_date', 'desc')
                    ->first();

                if ($latestReading) {
                    $latestReadingDate  = Carbon::make($latestReading->reading_date);
                    $readingStartMonth  = $readingDate->copy()->startOfMonth();
                    $latestStartMonth   = $latestReadingDate->copy()->startOfMonth();

                    // must be strictly after the last reading month
                    if ($readingStartMonth->lte($latestStartMonth)) {
                        return $fail(
                            'Reading date must be in a month after ' .
                            $latestReadingDate->format('F Y')
                        );
                    }

                    // if exactly next month, must be same day or later
                    if (
                        $readingStartMonth->eq($latestStartMonth->copy()->addMonth()) &&
                        $readingDate->day < $latestReadingDate->day
                    ) {
                        return $fail(
                            'Reading date must be on or after ' .
                            $latestReadingDate->copy()->addMonth()->format('d M Y')
                        );
                    }
                } else {
                    // first reading must be after rental start
                    $rentalStart = Carbon::make($rental->start_date);
                    $readingStartMonth = $readingDate->copy()->startOfMonth();
                    $rentalStartMonth  = $rentalStart->copy()->startOfMonth();

                    if ($readingStartMonth->lte($rentalStartMonth)) {
                        return $fail(
                            'First reading must be in a month after rental start (' .
                            $rentalStart->format('F Y') . ')'
                        );
                    }

                    if (
                        $readingStartMonth->eq($rentalStartMonth->copy()->addMonth()) &&
                        $readingDate->day < $rentalStart->day
                    ) {
                        return $fail(
                            'First reading date must be on or after ' .
                            $rentalStart->copy()->addMonth()->format('d M Y')
                        );
                    }
                }

                // duplicate month check
                $exists = $rental->utilityUsages()
                    ->whereYear('reading_date', $readingDate->year)
                    ->whereMonth('reading_date', $readingDate->month)
                    ->exists();

                if ($exists) {
                    $fail('A utility reading already exists for ' . $readingDate->format('F Y'));
                }
            },
        ],
    ], $messages);

    try {
        DB::beginTransaction();

        $readingDate = Carbon::make($validated['reading_date']);

        // previous usage is required
        $previous = $rental->utilityUsages()
            ->where('reading_date', '<', $readingDate)
            ->orderBy('reading_date', 'desc')
            ->first();

        if (!$previous) {
            throw new \Exception(
                'Cannot find previous meter readings. Please record initial readings first.'
            );
        }

        $waterUsed    = max(0, $validated['water_usage']    - $previous->water_usage);
        $electricUsed = max(0, $validated['electric_usage'] - $previous->electric_usage);

        $utilityUsage = UtilityUsage::create([
            'rental_id'      => $rental->id,
            'water_usage'    => $validated['water_usage'],
            'electric_usage' => $validated['electric_usage'],
            'reading_date'   => $readingDate,
            'notes'          => $validated['notes'] ?? null,
        ]);

        // adjust if your column names differ (rate vs fee)
        $waterRate   = $rental->room->water_fee   ?? 0;
        $electricRate= $rental->room->electric_fee?? 0;

        $waterAmount   = $waterUsed   * $waterRate;
        $electricAmount= $electricUsed* $electricRate;

        $detailNotes = sprintf(
            "Water Usage: %.2fm³ (Current: %.2fm³ - Previous: %.2fm³) at ₱%.2f/m³ = ₱%.2f\n".
            "Electric Usage: %.2fkWh (Current: %.2fkWh - Previous: %.2fkWh) at ₱%.2f/kWh = ₱%.2f",
            $waterUsed, $validated['water_usage'], $previous->water_usage, $waterRate, $waterAmount,
            $electricUsed, $validated['electric_usage'], $previous->electric_usage, $electricRate, $electricAmount
        );

        Invoice::create([
            'rental_id'        => $rental->id,
            'utility_usage_id' => $utilityUsage->id,
            'billing_date'     => $readingDate,
            'due_date'         => $readingDate->copy()->addDays(10),
            'rent_amount'      => $rental->room->monthly_rent ?? 0,
            'total_water_fee'  => $waterAmount,
            'total_electric_fee'=> $electricAmount,
            'total_amount'     => ($rental->room->monthly_rent ?? 0) + $waterAmount + $electricAmount,
            'status'           => Invoice::STATUS_PENDING,
            'notes'            => $detailNotes,
        ]);

        DB::commit();

        return redirect()
            ->route('rooms.show', $rental->room)
            ->with('success', 'Utility usage added successfully.');
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to record utility usage: ' . $e->getMessage());
    }
}


    public function storeQuickAddRental(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100|unique:tenants',
            'id_card_number' => 'required|string|max:20|unique:tenants',
            'id_card_front' => 'nullable|image|max:2048',
            'id_card_back' => 'nullable|image|max:2048',

            'deposit' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',

            'electric_usage' => 'nullable|numeric|min:0',
            'water_usage' => 'nullable|numeric|min:0',
            'reading_date' => 'nullable|date',
        ]);

        // Create tenant
        $tenantData = $request->only(['name', 'phone', 'email', 'id_card_number', 'id_card_front', 'id_card_back']);
        if ($request->hasFile('id_card_front')) {
            $tenantData['id_card_front_path'] = $request->file('id_card_front')->store('id-cards', 'public');
        }
        if ($request->hasFile('id_card_back')) {
            $tenantData['id_card_back_path'] = $request->file('id_card_back')->store('id-cards', 'public');
        }
        $tenant = \App\Models\Tenant::create($tenantData);

        // Create new rental
        $rental = $room->rentals()->create([
            'tenant_id' => $tenant->id,
            'room_id' => $room->id,
            'deposit' => $request->deposit,
            'start_date' => $request->start_date,
        ]);

        if($rental) {
            // Update room status to occupied
            $room->status = 'occupied';
            $room->save();
        }

        // Initialize utility usage if not already initialized
        if ($room->activeRentals()->count() === 1) {
            $rental->utilityUsages()->create([
                'rental_id' => $rental->id,
                'water_usage' => $request->water_usage ?? 0,
                'electric_usage' => $request->electric_usage ?? 0,
                'reading_date' => $request->reading_date ?? 0,
            ]);
        }

        return redirect()->route('rooms.index')
            ->with('success', 'Rental created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $validated = $request->validated();

        // Create new room instance
        $room = new Room($validated);

        // Handle main image upload
        if ($image = $request->validated()['image'] ?? null) {
            $path = Storage::disk('public')->put('rooms', $image);
            $room->image = $path;
        }

        $room->save();

        // Handle additional images
        if ($additionalImages = $request->validated()['additional_images'] ?? null) {
            foreach ($additionalImages as $image) {
                $path = Storage::disk('public')->put('rooms', $image);
                $room->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        // $room->load(['building', 'rental.tenant', 'rental.utilityUsages']);

        $room->load([
        'building',
        'activeRentals.tenant',
        'activeRentals.utilityUsages'
        ])->loadCount('activeRentals');

        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $buildings = Building::where('user_id', Auth::id())->get();
        return view('admin.rooms.edit', compact('room', 'buildings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $validated = $request->validated();

        if (isset($validated['building_id'])) {
            // Verify new building belongs to user if building is being changed
            Building::where('user_id', Auth::id())
                ->findOrFail($validated['building_id']);
        }

        // Handle main image upload
        if ($image = $request->validated()['image'] ?? null) {
            // Delete old image if exists
            if ($room->image && Storage::disk('public')->exists($room->image)) {
                Storage::disk('public')->delete($room->image);
            }
            $path = Storage::disk('public')->put('rooms', $image);
            $validated['image'] = $path;
        }

        $room->update($validated);

        // Handle additional images
        if ($additionalImages = $request->validated()['additional_images'] ?? null) {
            foreach ($additionalImages as $image) {
                $path = Storage::disk('public')->put('rooms', $image);
                $room->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
