<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $validated = $request->validated();

        // Verify building belongs to user
        $building = Building::where('user_id', Auth::id())
            ->findOrFail($validated['building_id']);

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
