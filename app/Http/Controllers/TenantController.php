<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    // public function __construct()
    // {
    //     // Only apply auth middleware to owner management routes
    //     $this->middleware('auth')->except(['search', 'showPublic', 'register']);
    // }

    /**
     * Handle tenant registration from public room page
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100|unique:tenants',
            'id_card_number' => 'required|string|max:20',
            'id_card_front' => 'required|image|max:2048',
            'id_card_back' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $room = Room::findOrFail($request->room_id);

        // Check if room is still available
        if ($room->status !== 'vacant') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sorry, this room is no longer available.');
        }

        // Create tenant
        $tenant = Tenant::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'id_card_number' => $request->id_card_number,
            'id_card_front_path' => $request->file('id_card_front')?->store('id-cards', 'public'),
            'id_card_back_path' => $request->file('id_card_back')?->store('id-cards', 'public'),
        ]);

        // Create rental
        $rental = $room->rentals()->create([
            'tenant_id' => $tenant->id,
            'start_date' => now(),
        ]);
        $room->update(['status' => 'occupied']);

        // Send notification to owner
        // TODO: Implement notification system

        return redirect()->route('rooms.public.show', $room)
            ->with('success', 'Thank you for your registration! We will contact you shortly.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::withCount('rentals')->latest()->paginate(10);
        return view('tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100|unique:tenants',
            'id_card_number' => 'required|string|max:20|unique:tenants',
            'id_card_front' => 'nullable|image|max:2048',
            'id_card_back' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'phone', 'email', 'id_card_number']);

        // Handle ID card front image upload
        if ($request->hasFile('id_card_front')) {
            $data['id_card_front_path'] = $request->file('id_card_front')->store('id-cards', 'public');
        }

        // Handle ID card back image upload
        if ($request->hasFile('id_card_back')) {
            $data['id_card_back_path'] = $request->file('id_card_back')->store('id-cards', 'public');
        }

        Tenant::create($data);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $tenant->load('rentals.room.building');
        return view('tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100|unique:tenants,email,' . $tenant->id,
            'id_card_number' => 'required|string|max:20|unique:tenants,id_card_number,',
            'id_card_front' => 'nullable|image|max:2048',
            'id_card_back' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'phone', 'email', 'id_card_number']);

        // Handle ID card front image upload
        if ($request->hasFile('id_card_front')) {
            // Delete old image if exists
            if ($tenant->id_card_front_path) {
                Storage::disk('public')->delete($tenant->id_card_front_path);
            }
            $data['id_card_front_path'] = $request->file('id_card_front')->store('id-cards', 'public');
        }

        // Handle ID card back image upload
        if ($request->hasFile('id_card_back')) {
            // Delete old image if exists
            if ($tenant->id_card_back_path) {
                Storage::disk('public')->delete($tenant->id_card_back_path);
            }
            $data['id_card_back_path'] = $request->file('id_card_back')->store('id-cards', 'public');
        }

        $tenant->update($data);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        // Delete ID card images if they exist
        if ($tenant->id_card_front_path) {
            Storage::disk('public')->delete($tenant->id_card_front_path);
        }
        if ($tenant->id_card_back_path) {
            Storage::disk('public')->delete($tenant->id_card_back_path);
        }

        $tenant->delete();

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }

    /**
     * Public search page for available rooms
     */
    public function search(Request $request)
    {
        $query = Room::with('building')
            ->where('status', 'vacant');

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('building')) {
            $query->whereHas('building', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->building . '%');
            });
        }

        $rooms = $query->latest()->paginate(12);

        return view('tenants.search', compact('rooms'));
    }

    /**
     * Public room details page
     */
    public function showPublic(Room $room)
    {
        if ($room->status !== 'vacant') {
            abort(404);
        }

        return view('tenants.room', compact('room'));
    }

}
