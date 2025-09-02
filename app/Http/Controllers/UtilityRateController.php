<?php

namespace App\Http\Controllers;

use App\Models\UtilityRate;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUtilityRateRequest;

class UtilityRateController extends Controller
{
    public function index()
    {
        $rates = UtilityRate::orderBy('effective_date', 'desc')->paginate(10);
        return view('utility-rates.index', compact('rates'));
    }

    public function create()
    {
        return view('utility-rates.create');
    }

    public function store(StoreUtilityRateRequest $request)
    {
        $rate = UtilityRate::create($request->validated());
        return redirect()->route('utility-rates.index')
            ->with('success', 'Utility rate created successfully.');
    }

    public function edit(UtilityRate $utilityRate)
    {
        return view('utility-rates.edit', compact('utilityRate'));
    }

    public function update(StoreUtilityRateRequest $request, UtilityRate $utilityRate)
    {
        $utilityRate->update($request->validated());
        return redirect()->route('utility-rates.index')
            ->with('success', 'Utility rate updated successfully.');
    }

    public function destroy(UtilityRate $utilityRate)
    {
        // Check if rate is being used
        if ($utilityRate->utilityUsages()->exists()) {
            return back()->with('error', 'Cannot delete rate as it is being used by utility readings.');
        }

        $utilityRate->delete();
        return redirect()->route('utility-rates.index')
            ->with('success', 'Utility rate deleted successfully.');
    }
}
