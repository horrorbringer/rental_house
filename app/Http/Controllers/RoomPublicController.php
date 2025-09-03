<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;

class RoomPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['building', 'images'])
            ->when($request->building, function ($q) use ($request) {
                return $q->where('building_id', $request->building);
            });

        $rooms = $query->latest()->paginate(12);
        $buildings = Building::all();

        return view('public.rooms.index', compact('rooms', 'buildings'));
    }

    public function search(Request $request)
    {
        $query = Room::with(['building', 'images']);

        // Filter by building
        if ($request->building) {
            $query->where('building_id', $request->building);
        }

        // Filter by price range
        if ($request->min_price) {
            $query->where('monthly_rent', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('monthly_rent', '<=', $request->max_price);
        }

        $rooms = $query->latest()->paginate(12);
        $buildings = Building::all();

        return view('public.rooms.index', [
            'rooms' => $rooms,
            'buildings' => $buildings,
            'filters' => $request->all()
        ]);
    }

    public function show(Room $room)
    {
        $room->load(['building', 'images']);
        
        // Get similar rooms from the same building
        $similarRooms = Room::with(['building', 'images'])
            ->where('building_id', $room->building_id)
            ->where('id', '!=', $room->id)
            ->take(3)
            ->get();

        return view('public.rooms.show', compact('room', 'similarRooms'));
    }
}
