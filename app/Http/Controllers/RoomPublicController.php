<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;

class RoomPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['building'])
            ->where('status', Room::STATUS_VACANT);

        // Filter by building
        if ($request->filled('building')) {
            $query->whereHas('building', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->building . '%');
            });
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('monthly_rent', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('monthly_rent', '<=', $request->price_max);
        }

        $rooms = $query->latest()->paginate(12);
        $buildings = Building::select('name')->distinct()->get();

        return view('public.rooms.index', compact('rooms', 'buildings'));
    }

    public function show(Room $room)
    {
        if ($room->status !== Room::STATUS_VACANT) {
            abort(404, 'Room is not available');
        }

        $room->load('building');
        return view('public.rooms.show', compact('room'));
    }
}
