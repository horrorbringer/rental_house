<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $stats = [
            'buildings' => Building::count(),
            'rooms' => Room::count(),
            'tenants' => Tenant::count(),
            'available_rooms' => Room::whereDoesntHave('rental')->count(),
        ];

        $featured_buildings = Building::with(['rooms' => function($query) {
            $query->whereDoesntHave('rental')->take(3);
        }])->take(3)->get();

        return view('welcome', compact('stats', 'featured_buildings'));
    }
}
