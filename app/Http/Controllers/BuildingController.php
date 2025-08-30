<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::withCount(['rooms', 'rooms as vacant_rooms_count' => function ($query) {
            $query->where('status', 'vacant');
        }])->latest()->get();

        return view('buildings.index', compact('buildings'));
    }

    public function create()
    {
        return view('buildings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $building = new Building($validated);
        $building->user_id = Auth::id();
        $building->save();

        return redirect()->route('buildings.index')
            ->with('success', 'Building created successfully.');
    }

    public function show(Building $building)
    {
        $building->load(['owner', 'rooms']);
        return view('buildings.show', compact('building'));
    }

    public function edit(Building $building)
    {
        return view('buildings.edit', compact('building'));
    }

    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $building->update($validated);

        return redirect()->route('buildings.index')
            ->with('success', 'Building updated successfully.');
    }

    public function destroy(Building $building)
    {
        $building->delete();

        return redirect()->route('buildings.index')
            ->with('success', 'Building deleted successfully.');
    }
}
