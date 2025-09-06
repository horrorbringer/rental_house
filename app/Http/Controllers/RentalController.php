<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Room;
use App\Models\Tenant;
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
        $rooms = Room::with('building')
            ->where('status', 'vacant')
            ->orderBy('room_number')
            ->get();

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

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tenant_id' => 'required|exists:tenants,id',
            'deposit' => 'nullable|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        DB::beginTransaction();
        try {
            // Check if room is still vacant
            $room = Room::where('id', $validated['room_id'])
                ->where('status', 'vacant')
                ->firstOrFail();

            // Check if tenant already has an active rental
            $hasActiveRental = Rental::where('tenant_id', $validated['tenant_id'])
                ->whereNull('end_date')
                ->exists();

            if ($hasActiveRental) {
                throw new \Exception('Tenant already has an active rental.');
            }

            // Set initial rental status
            $validated['status'] = 'active';
            
            // Create rental and update room status
            $rental = Rental::create($validated);
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
