<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Rental;
use App\Models\UtilityUsage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UtilityUsageController extends Controller
{
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $utilityUsages = UtilityUsage::with(['rental.room.building', 'rental.tenant'])
            ->orderBy('reading_date', 'desc')
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
        $messages = [
            'rental_id.required' => 'Please select a rental unit',
            'rental_id.exists' => 'The selected rental unit is invalid',
            'water_usage.required' => 'Please enter the water usage',
            'water_usage.numeric' => 'Water usage must be a number',
            'water_usage.min' => 'Water usage cannot be negative',
            'electric_usage.required' => 'Please enter the electric usage',
            'electric_usage.numeric' => 'Electric usage must be a number',
            'electric_usage.min' => 'Electric usage cannot be negative',
            'reading_date.required' => 'Please select a reading date',
            'reading_date.date' => 'Invalid reading date format',
        ];

        // First validate all inputs
        $validated = $request->validate([
            'rental_id' => ['required', 'exists:rentals,id'],
            'water_usage' => ['required', 'numeric', 'min:0'],
            'electric_usage' => ['required', 'numeric', 'min:0'],
            'reading_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], $messages);

        try {
            DB::beginTransaction();

            // Get rental information with previous utility usage
            $rental = Rental::with(['room', 'utilityUsages' => function($query) use ($validated) {
                $query->where('reading_date', '<', $validated['reading_date'])
                    ->orderBy('reading_date', 'desc');
            }])->findOrFail($validated['rental_id']);

            // Get previous readings
            $previousUsage = $rental->utilityUsages->first();
            $previousWaterReading = $previousUsage ? $previousUsage->water_usage : 0;
            $previousElectricReading = $previousUsage ? $previousUsage->electric_usage : 0;

            // Calculate actual usage (current reading - previous reading)
            $waterUsage = max(0, $validated['water_usage'] - $previousWaterReading);
            $electricUsage = max(0, $validated['electric_usage'] - $previousElectricReading);

            // Create the utility usage record with current meter readings
            $utilityUsage = UtilityUsage::create([
                'rental_id' => $validated['rental_id'],
                'water_usage' => $validated['water_usage'], // Store the actual meter reading
                'electric_usage' => $validated['electric_usage'], // Store the actual meter reading
                'reading_date' => $validated['reading_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Calculate charges based on actual usage and room rates
            $waterAmount = $waterUsage * ($rental->room->water_fee ?? 0);
            $electricAmount = $electricUsage * ($rental->room->electric_fee ?? 0);

            // Prepare the usage notes
            $notes = sprintf(
                "Water Usage: %.2fm³ (Current: %.2fm³ - Previous: %.2fm³) at ₱%.2f/m³ = ₱%.2f\n" .
                "Electric Usage: %.2fkWh (Current: %.2fkWh - Previous: %.2fkWh) at ₱%.2f/kWh = ₱%.2f",
                $waterUsage,
                $validated['water_usage'],
                $previousWaterReading,
                $rental->room->water_rate ?? 0,
                $waterAmount,
                $electricUsage,
                $validated['electric_usage'],
                $previousElectricReading,
                $rental->room->electric_rate ?? 0,
                $electricAmount
            );

            // Create invoice - number will be generated automatically by the model
            $invoice = Invoice::create([
                'rental_id' => $validated['rental_id'],
                'utility_usage_id' => $utilityUsage->id,
                'billing_date' => now(),
                'due_date' => now()->addDays(10),
                'rent_amount' => $rental->room->monthly_rent ?? 0,
                'total_water_fee' => $waterAmount,
                'total_electric_fee' => $electricAmount,
                'status' => Invoice::STATUS_PENDING,
                'notes' => $notes
            ]);            

            // Send notification or email to tenant about the new invoice
            // TODO: Implement notification system
            
            DB::commit();

            return redirect()->route('utility-usages.index')
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
            'reading_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $rental = $utilityUsage->rental()->with('room')->first();
            
            // Update utility usage
            $utilityUsage->update([
                'water_usage' => $validated['water_usage'],
                'electric_usage' => $validated['electric_usage'],
                'reading_date' => $validated['reading_date'],
                'billing_month' => Carbon::parse($validated['reading_date'])->format('Y-m-01'),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Recalculate charges
            $waterAmount = $validated['water_usage'] * ($rental->room->water_rate ?? 0);
            $electricAmount = $validated['electric_usage'] * ($rental->room->electric_rate ?? 0);

            // Update associated invoice
            $invoice = Invoice::where('utility_usage_id', $utilityUsage->id)->first();

            if ($invoice) {
                $total = ($rental->room->monthly_rent ?? 0) + $waterAmount + $electricAmount;
                $invoice->update([
                    'total_water_fee' => $waterAmount,
                    'total_electric_fee' => $electricAmount,
                    'total_amount' => $total,
                    'notes' => "Water Usage: {$validated['water_usage']}m³ at ₱{$rental->room->water_rate}/m³ = ₱{$waterAmount}\nElectric Usage: {$validated['electric_usage']}kWh at ₱{$rental->room->electric_rate}/kWh = ₱{$electricAmount}"
                ]);
            }

            DB::commit();

            return redirect()
                ->route('utility-usages.index')
                ->with('success', 'Utility usage and invoice updated successfully.');

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
            
            // Get all active rentals
            $rentals = Rental::whereNull('end_date')
                ->with('room')
                ->get();

            $billingDate = now();
            $count = 0;

            foreach ($rentals as $rental) {
                // Check if utility usage already exists for this month
                $exists = UtilityUsage::where('rental_id', $rental->id)
                    ->whereMonth('billing_month', $billingDate->month)
                    ->whereYear('billing_month', $billingDate->year)
                    ->exists();

                if (!$exists) {
                    // Create utility usage with zero readings
                    $utilityUsage = UtilityUsage::create([
                        'rental_id' => $rental->id,
                        'water_usage' => 0,
                        'electric_usage' => 0,
                        'reading_date' => $billingDate,
                        'billing_month' => $billingDate->format('Y-m-01'),
                    ]);

                    // Create invoice with zero utility charges
                    Invoice::create([
                        'rental_id' => $rental->id,
                        'utility_usage_id' => $utilityUsage->id,
                        'billing_date' => $billingDate,
                        'due_date' => $billingDate->copy()->addDays(10),
                        'rent_amount' => $rental->room->monthly_rent ?? 0,
                        'total_water_fee' => 0,
                        'total_electric_fee' => 0,
                        'status' => Invoice::STATUS_DRAFT,
                        'notes' => sprintf(
                            "Water Usage: 0m³ at ₱%.2f/m³ = ₱0\n" .
                            "Electric Usage: 0kWh at ₱%.2f/kWh = ₱0",
                            $rental->room->water_rate ?? 0,
                            $rental->room->electric_rate ?? 0
                        )
                    ]);

                    $count++;
                }
            }

            DB::commit();

            return redirect()
                ->route('utility-usages.index')
                ->with('success', "Generated {$count} new utility usage records for {$billingDate->format('F Y')}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate monthly utility usages: ' . $e->getMessage());
        }
    }
}

