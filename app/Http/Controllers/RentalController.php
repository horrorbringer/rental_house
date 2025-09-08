<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\UtilityUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentals = Rental::with(['room', 'tenant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.rentals.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Load available rooms
        $rooms = Room::with(['latestRental', 'building'])
            ->get()
            ->map(function($room) {
                // Add a flag to indicate if room has active rental
                $room->hasActiveRental = $room->latestRental && !$room->latestRental->end_date;
                return $room;
            })
            ->sortBy('room_number');

        // Load tenants that are not currently in any active rental
        $tenants = Tenant::select('tenants.*')
            ->leftJoin('rentals', function($join) {
                $join->on('tenants.id', '=', 'rentals.tenant_id')
                    ->whereNull('rentals.end_date');
            })
            ->whereNull('rentals.id')
            ->orderBy('tenants.name')
            ->get();

        return view('admin.rentals.create', compact('rooms', 'tenants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Convert empty string deposit to null
        if ($request->has('deposit') && $request->deposit === '') {
            $request->merge(['deposit' => null]);
        }

                $messages = [
            'room_id.required' => 'Please select a room',
            'room_id.exists' => 'The selected room is invalid',
            'tenant_id.required' => 'Please select a tenant',
            'tenant_id.exists' => 'The selected tenant is invalid',
            'deposit.numeric' => 'Deposit amount must be a number',
            'deposit.min' => 'Deposit amount cannot be negative',
            'start_date.required' => 'Please select a start date',
            'start_date.date' => 'Invalid start date format',
            'start_date.after_or_equal' => 'Start date must be today or a future date',
            'water_usage.required' => 'Please enter the initial water meter reading',
            'water_usage.numeric' => 'Water meter reading must be a number',
            'water_usage.min' => 'Water meter reading cannot be negative',
            'electric_usage.required' => 'Please enter the initial electric meter reading',
            'electric_usage.numeric' => 'Electric meter reading must be a number',
            'electric_usage.min' => 'Electric meter reading cannot be negative',
            'reading_date.required' => 'Please select the meter reading date',
            'reading_date.date' => 'Invalid reading date format',
            'notes.max' => 'Notes cannot exceed 255 characters',
        ];

        // Check if room has active rentals first
        $room = Room::with(['activeRentals'])->findOrFail($request->room_id);
        $hasActiveRentals = $room->activeRentals->count() > 0;

        // Build validation rules based on whether room has active rentals
        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'tenant_id' => 'required|exists:tenants,id',
            'deposit' => 'nullable|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:255',
        ];

        // Only require utility readings for first tenant
        if (!$hasActiveRentals) {
            $rules['water_usage'] = 'required|numeric|min:0';
            $rules['electric_usage'] = 'required|numeric|min:0';
            $rules['reading_date'] = [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $readingDate = \Carbon\Carbon::parse($value);
                    $startDate = \Carbon\Carbon::parse($request->start_date);
                    
                    // Reading date must be within the same month as start date
                    if ($readingDate->format('Y-m') !== $startDate->format('Y-m')) {
                        $fail('Initial meter reading date must be in the same month as the rental start date (' . $startDate->format('F Y') . ').');
                    }
                }
            ];
        }

        $validated = $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            // Get the room and its current active rentals
            $room = Room::with(['latestRental', 'activeRentals'])
                ->where('id', $validated['room_id'])
                ->firstOrFail();

            // Check if tenant already has an active rental
            $hasActiveRental = Rental::where('tenant_id', $validated['tenant_id'])
                ->whereNull('end_date')
                ->exists();

            if ($hasActiveRental) {
                throw new \Exception('This tenant already has an active rental. One tenant cannot rent multiple rooms.');
            }

            // Set initial rental status
            $validated['status'] = 'active';
            
            // Create rental
            $rental = Rental::create([
                'room_id' => $validated['room_id'],
                'tenant_id' => $validated['tenant_id'],
                'deposit' => $validated['deposit'],
                'start_date' => $validated['start_date'],
                'status' => 'active'
            ]);

            // Only create utility usage for first tenant
            if (!$hasActiveRentals && isset($validated['water_usage'])) {
                UtilityUsage::create([
                    'rental_id' => $rental->id,
                    'water_usage' => $validated['water_usage'],
                    'electric_usage' => $validated['electric_usage'],
                    'reading_date' => $validated['reading_date'],
                    'notes' => sprintf(
                        "Initial meter readings - Water: %.2f m³, Electric: %.2f kWh",
                        $validated['water_usage'],
                        $validated['electric_usage']
                    ),
                    'is_initial_reading' => true,
                ]);
            }

            // Update room status
            $room->update(['status' => 'occupied']);

            DB::commit();

            return redirect()
                ->route('rentals.index')
                ->with('success', 'Rental created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', $e->getMessage() ?: 'Failed to create rental.');
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        $rental->load(['room', 'tenant', 'utilityUsages']);
        return view('admin.rentals.show', compact('rental'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        $rental->load(['room', 'tenant']);
        return view('admin.rentals.edit', compact('rental'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'end_date' => [
                'required',
                'date',
                'after:' . $rental->start_date,
                'before_or_equal:' . now()->addDays(1)->format('Y-m-d')
            ],
            'status' => [
                'required',
                'in:' . implode(',', Rental::$statuses)
            ],
        ]);

        DB::beginTransaction();
        try {
            // Update the rental with end date
            $rental->update($validated);

            // Get the room and its current active tenants count
            // When ending a rental
            $room = $rental->room;
            $hasActiveRentals = $room->rentals()
                ->whereNull('end_date')
                ->exists();

            $room->update([
                'status' => $hasActiveRentals ? 'occupied' : 'vacant'
            ]);

            DB::commit();

            return redirect()
                ->route('rentals.index')
                ->with('success', 'Rental ended successfully. Room status has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to end rental. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        try {
            $rental->delete();
            return redirect()
                ->route('rentals.index')
                ->with('success', 'Rental deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete rental.');
        }
    }
}
