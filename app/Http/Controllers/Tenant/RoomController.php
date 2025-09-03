<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['building', 'images'])
            ->when($request->building, function ($q) use ($request) {
                return $q->where('building_id', $request->building);
            })
            ->when($request->min_price, function ($q) use ($request) {
                return $q->where('monthly_rent', '>=', $request->min_price);
            })
            ->when($request->max_price, function ($q) use ($request) {
                return $q->where('monthly_rent', '<=', $request->max_price);
            });

        $rooms = $query->paginate(12);

        return view('tenant.rooms.index', compact('rooms'));
    }

    public function show(Room $room)
    {
        $room->load(['building', 'images']);
        
        return view('tenant.rooms.show', compact('room'));
    }
}
