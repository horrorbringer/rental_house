<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::withCount('rentals')->latest()->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tenants.create');
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
        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
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

}
