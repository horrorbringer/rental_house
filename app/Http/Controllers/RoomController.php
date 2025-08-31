<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $rooms = $query->latest()->paginate(12);
        $buildings = Building::where('user_id', Auth::id())->get();

        return view('rooms.index', compact('rooms', 'buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buildings = Building::where('user_id', Auth::id())->get();
        return view('rooms.create', compact('buildings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $validated = $request->validated();

        // Verify building belongs to user
        $building = Building::where('user_id', Auth::id())
            ->findOrFail($validated['building_id']);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rooms', 'public');
            $validated['image'] = $path;
        }

        $room = new Room($validated);
        $room->save();

        // Handle additional images
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('rooms', 'public');
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
        $room->load(['building', 'rental.tenant', 'rental.utilityUsage']);
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $buildings = Building::where('user_id', Auth::id())->get();
        return view('rooms.edit', compact('room', 'buildings'));
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

        $room->update($validated);

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
