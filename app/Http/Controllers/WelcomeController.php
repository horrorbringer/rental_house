<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get featured rooms (vacant rooms with images)
        $featuredRooms = Room::with(['building'])
            ->where('status', Room::STATUS_VACANT)
            ->latest()
            ->take(6)
            ->get();

        // Get some stats
        $stats = [
            'buildings' => Building::count(),
            'rooms' => Room::count(),
            'available_rooms' => Room::where('status', Room::STATUS_VACANT)->count(),
        ];

        return view('welcome', compact('featuredRooms', 'stats'));
    }

    public function search(Request $request)
    {
        $query = Room::query()->with(['building']);

        if ($request->filled('price_min')) {
            $query->where('monthly_rent', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('monthly_rent', '<=', $request->price_max);
        }

        if ($request->filled('building')) {
            $query->whereHas('building', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->building . '%');
            });
        }

        // Only show vacant rooms
        $query->where('status', Room::STATUS_VACANT);

        $rooms = $query->latest()->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.room-cards', compact('rooms'))->render(),
                'hasMorePages' => $rooms->hasMorePages()
            ]);
        }

        return view('welcome', compact('rooms'));
    }
}
