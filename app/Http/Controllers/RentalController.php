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
        // Load rooms with active rentals count and room details
        $rooms = Room::select('rooms.*')
            ->selectRaw('COUNT(DISTINCT rentals.id) as active_tenants_count')
            ->leftJoin('rentals', function($join) {
                $join->on('rooms.id', '=', 'rentals.room_id')
                    ->whereNull('rentals.end_date');
            })
            ->with('building')  // Eager load building relationship
            ->where('status', '!=', Room::STATUS_FULL)
            ->groupBy('rooms.id')
            ->havingRaw('active_tenants_count < rooms.capacity')
            ->orderBy('room_number')
            ->get()
            ->map(function ($room) {
                $room->available_slots = $room->capacity - $room->active_tenants_count;
                return $room;
            });

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
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Check room capacity and current occupancy
            $room = Room::select('rooms.*')
                ->selectRaw('COUNT(DISTINCT rentals.id) as active_tenants_count')
                ->leftJoin('rentals', function($join) {
                    $join->on('rooms.id', '=', 'rentals.room_id')
                        ->whereNull('rentals.end_date');
                })
                ->where('rooms.id', $validated['room_id'])
                ->groupBy('rooms.id')
                ->firstOrFail();

            // Check if room is full or tenant is already in an active rental
            if ($room->active_tenants_count >= $room->capacity) {
                throw new \Exception('This room has reached its maximum capacity.');
            }

            // Check if tenant already has an active rental
            $tenantHasActiveRental = Rental::where('tenant_id', $validated['tenant_id'])
                ->whereNull('end_date')
                ->exists();

            if ($tenantHasActiveRental) {
                throw new \Exception('This tenant already has an active rental.');
            }

            // Create the rental
            $rental = Rental::create($validated);

            // Update room status if it reaches capacity
            if ($room->active_tenants_count + 1 >= $room->capacity) {
                $room->update(['status' => Room::STATUS_FULL]);
            } elseif ($room->active_tenants_count + 1 > 0) {
                $room->update(['status' => Room::STATUS_OCCUPIED]);
            }

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
        ]);

        DB::beginTransaction();
        try {
            // Update the rental with end date
            $rental->update($validated);

            // Get the room and its current active tenants count
            $room = $rental->room;
            $activeTenantsCount = $room->rentals()->whereNull('end_date')->count();

            // Update room status based on occupancy
            if ($activeTenantsCount === 0) {
                $room->update(['status' => Room::STATUS_VACANT]);
            } elseif ($activeTenantsCount < $room->capacity) {
                $room->update(['status' => Room::STATUS_OCCUPIED]);
            }

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
