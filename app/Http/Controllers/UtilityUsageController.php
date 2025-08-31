<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Rental;
use App\Models\UtilityUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtilityUsageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $utilityUsages = UtilityUsage::with(['rental.room', 'rental.tenant'])
            ->orderBy('billing_month', 'desc')
            ->paginate(10);

        return view('admin.utility-usages.index', compact('utilityUsages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rentals = Rental::whereNull('end_date')
            ->with(['room', 'tenant'])
            ->get();

        return view('admin.utility-usages.create', compact('rentals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'billing_month' => 'required|date',
            'water_usage' => 'required|numeric|min:0',
            'electric_usage' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $rental = Rental::with('room')->findOrFail($validated['rental_id']);
            
            // Create utility usage record
            $utilityUsage = UtilityUsage::create([
                'rental_id' => $validated['rental_id'],
                'billing_month' => $validated['billing_month'],
                'water_usage' => $validated['water_usage'],
                'electric_usage' => $validated['electric_usage'],
                'water_price' => $rental->room->water_fee,
                'electric_price' => $rental->room->electric_fee,
            ]);

            // Calculate total amount for invoice
            $waterAmount = $validated['water_usage'] * $rental->room->water_fee;
            $electricAmount = $validated['electric_usage'] * $rental->room->electric_fee;

            // Create invoice
            Invoice::create([
                'rental_id' => $validated['rental_id'],
                'billing_month' => $validated['billing_month'],
                'rent_amount' => $rental->room->monthly_rent,
                'water_fee' => $rental->room->water_fee,
                'electric_fee' => $rental->room->electric_fee,
                'water_usage_amount' => $waterAmount,
                'electric_usage_amount' => $electricAmount,
                'total' => $rental->room->monthly_rent + $waterAmount + $electricAmount,
                'status' => 'unpaid'
            ]);

            DB::commit();

            return redirect()
                ->route('utility-usages.index')
                ->with('success', 'Utility usage recorded and invoice generated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record utility usage: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UtilityUsage $utilityUsage)
    {
        $utilityUsage->load(['rental.room', 'rental.tenant', 'rental.invoices']);
        return view('admin.utility-usages.show', compact('utilityUsage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UtilityUsage $utilityUsage)
    {
        $utilityUsage->load(['rental.room', 'rental.tenant']);
        return view('admin.utility-usages.edit', compact('utilityUsage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UtilityUsage $utilityUsage)
    {
        $validated = $request->validate([
            'water_usage' => 'required|numeric|min:0',
            'electric_usage' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $rental = $utilityUsage->rental;
            
            // Update utility usage
            $utilityUsage->update([
                'water_usage' => $validated['water_usage'],
                'electric_usage' => $validated['electric_usage'],
            ]);

            // Recalculate invoice amounts
            $waterAmount = $validated['water_usage'] * $utilityUsage->water_price;
            $electricAmount = $validated['electric_usage'] * $utilityUsage->electric_price;

            // Update associated invoice
            $invoice = Invoice::where('rental_id', $rental->id)
                ->where('billing_month', $utilityUsage->billing_month)
                ->first();

            if ($invoice) {
                $invoice->update([
                    'water_usage_amount' => $waterAmount,
                    'electric_usage_amount' => $electricAmount,
                    'total' => $rental->room->monthly_rent + $waterAmount + $electricAmount,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('utility-usages.index')
                ->with('success', 'Utility usage updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update utility usage: ' . $e->getMessage());
        }
    }

    /**
     * Generate utility usages for all active rentals.
     */
    public function generateMonthly()
    {
        try {
            DB::beginTransaction();

            $currentMonth = Carbon::now()->startOfMonth();
            
            // Get all active rentals
            $rentals = Rental::whereNull('end_date')
                ->with('room')
                ->get();

            $count = 0;
            foreach ($rentals as $rental) {
                // Check if utility usage already exists for this month
                $exists = UtilityUsage::where('rental_id', $rental->id)
                    ->where('billing_month', $currentMonth)
                    ->exists();

                if (!$exists) {
                    // Create utility usage with zero readings
                    UtilityUsage::create([
                        'rental_id' => $rental->id,
                        'billing_month' => $currentMonth,
                        'water_usage' => 0,
                        'electric_usage' => 0,
                        'water_price' => $rental->room->water_fee,
                        'electric_price' => $rental->room->electric_fee,
                    ]);

                    // Create invoice
                    Invoice::create([
                        'rental_id' => $rental->id,
                        'billing_month' => $currentMonth,
                        'rent_amount' => $rental->room->monthly_rent,
                        'water_fee' => $rental->room->water_fee,
                        'electric_fee' => $rental->room->electric_fee,
                        'water_usage_amount' => 0,
                        'electric_usage_amount' => 0,
                        'total' => $rental->room->monthly_rent,
                        'status' => 'unpaid'
                    ]);

                    $count++;
                }
            }

            DB::commit();

            return redirect()
                ->route('utility-usages.index')
                ->with('success', "Generated {$count} new utility usage records for {$currentMonth->format('F Y')}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate monthly utility usages: ' . $e->getMessage());
        }
    }
}

