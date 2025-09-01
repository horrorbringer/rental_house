<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'description' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $building = new Building($validated);
            $building->user_id = Auth::id();
            $building->save();

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('buildings', 'public');
                    
                    $building->images()->create([
                        'path' => $path,
                        'is_primary' => $index === 0, // First image will be primary
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('buildings.index')
                ->with('success', 'Building created successfully with images.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create building. ' . $e->getMessage());
        }
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
            'description' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'deleted_image_ids' => 'nullable|array',
            'deleted_image_ids.*' => 'integer|exists:building_images,id',
            'primary_image_id' => 'nullable|integer|exists:building_images,id',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update basic building information efficiently
            $building->updateOrFail([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'description' => $validated['description'] ?? null,
                'contact_info' => $validated['contact_info'] ?? null,
            ]);

            // 2. Process image deletions in bulk if any
            if (!empty($validated['deleted_image_ids'])) {
                $imagesToDelete = $building->images()
                    ->whereIn('id', $validated['deleted_image_ids'])
                    ->get(['id', 'path']);

                // Delete files in bulk
                Storage::disk('public')->delete(
                    $imagesToDelete->pluck('path')->toArray()
                );

                // Delete records in bulk
                $building->images()
                    ->whereIn('id', $validated['deleted_image_ids'])
                    ->delete();
            }

            // 3. Handle new image uploads efficiently
            if ($request->hasFile('new_images')) {
                $newImages = collect($request->file('new_images'))->map(function ($file) {
                    $path = $file->store('buildings', 'public');
                    return [
                        'path' => $path,
                        'is_primary' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                })->toArray();

                // Bulk insert new images
                $building->images()->insert($newImages);
            }

            // 4. Update primary image status efficiently
            if ($request->filled('primary_image_id')) {
                // Update all images in a single query
                $building->images()->update([
                    'is_primary' => DB::raw('CASE WHEN id = ' . 
                        $request->primary_image_id . ' THEN true ELSE false END')
                ]);
            } else {
                // Ensure at least one primary image exists
                $firstImage = $building->images()->first();
                if ($firstImage && !$building->images()->where('is_primary', true)->exists()) {
                    $firstImage->update(['is_primary' => true]);
                }
            }

            DB::commit();

            // 5. Return appropriate response based on request type
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Building updated successfully',
                    'data' => [
                        'building' => $building->fresh(['images' => function($query) {
                            $query->select('id', 'building_id', 'path', 'is_primary')
                                  ->orderBy('is_primary', 'desc')
                                  ->orderBy('created_at', 'desc');
                        }])
                    ]
                ]);
            }

            return redirect()
                ->route('buildings.index')
                ->with('success', 'Building updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Building update failed: ' . $e->getMessage(), [
                'building_id' => $building->id,
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            $errorMessage = app()->environment('production') 
                ? 'Failed to update building. Please try again.'
                : 'Failed to update building: ' . $e->getMessage();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessage,
                    'errors' => ['general' => [$errorMessage]]
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    public function destroy(Building $building)
    {
        DB::beginTransaction();
        try {
            // Delete related images first (files + DB records)
            foreach ($building->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }

            // Delete building
            $building->delete();

            DB::commit();
            return redirect()->route('buildings.index')
                ->with('success', 'Building deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Failed to delete building. ' . $e->getMessage());
        }
    }
}