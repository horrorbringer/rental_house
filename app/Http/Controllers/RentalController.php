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
        $rooms = Room::whereDoesntHave('activeRental')
            ->orderBy('room_number')
            ->get();
        $tenants = Tenant::orderBy('name')->get();

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

        // Check if room is available
        $roomHasActiveRental = Room::find($validated['room_id'])
            ->activeRental()
            ->exists();

        if ($roomHasActiveRental) {
            return back()->with('error', 'This room is already rented.');
        }

        DB::beginTransaction();
        try {
            Rental::create($validated);
            DB::commit();

            return redirect()
                ->route('rentals.index')
                ->with('success', 'Rental created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create rental.');
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
            'end_date' => 'required|date|after:start_date',
        ]);

        DB::beginTransaction();
        try {
            $rental->update($validated);
            DB::commit();

            return redirect()
                ->route('rentals.index')
                ->with('success', 'Rental updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update rental.');
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
