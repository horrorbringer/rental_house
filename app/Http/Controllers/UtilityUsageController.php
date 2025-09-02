<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Rental;
use App\Models\UtilityRate;
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
        $utilityUsages = UtilityUsage::with(['rental.room.building', 'rental.tenant', 'utilityRate'])
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
            
        $utilityRates = UtilityRate::orderBy('effective_date', 'desc')->get();

        return view('admin.utility-usages.create', compact('rentals', 'utilityRates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'rental_id.required' => 'Please select a rental unit',
            'rental_id.exists' => 'The selected rental unit is invalid',
            'reading_date.required' => 'Please enter the reading date',
            'reading_date.date' => 'The reading date must be a valid date',
            'water_meter_start.required' => 'Please enter the water meter start reading',
            'water_meter_start.numeric' => 'Water meter start reading must be a number',
            'water_meter_start.min' => 'Water meter start reading cannot be negative',
            'water_meter_end.required' => 'Please enter the water meter end reading',
            'water_meter_end.numeric' => 'Water meter end reading must be a number',
            'water_meter_end.min' => 'Water meter end reading cannot be negative',
            'water_meter_end.gt' => 'Water meter end reading must be greater than start reading',
            'electric_meter_start.required' => 'Please enter the electric meter start reading',
            'electric_meter_start.numeric' => 'Electric meter start reading must be a number',
            'electric_meter_start.min' => 'Electric meter start reading cannot be negative',
            'electric_meter_end.required' => 'Please enter the electric meter end reading',
            'electric_meter_end.numeric' => 'Electric meter end reading must be a number',
            'electric_meter_end.min' => 'Electric meter end reading cannot be negative',
            'electric_meter_end.gt' => 'Electric meter end reading must be greater than start reading',
            'utility_rate_id.required' => 'Please select a utility rate',
            'utility_rate_id.exists' => 'The selected utility rate is invalid',
            '*.image' => 'The file must be an image (jpeg, png, bmp, gif, svg, or webp)',
            '*.max' => 'The image size must not be greater than 2MB',
        ];

        // First validate all inputs
        $validated = $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'reading_date' => 'required|date',
            'water_meter_start' => 'required|numeric|min:0',
            'water_meter_end' => 'required|numeric|min:0|gt:water_meter_start',
            'electric_meter_start' => 'required|numeric|min:0',
            'electric_meter_end' => 'required|numeric|min:0|gt:electric_meter_start',
            'utility_rate_id' => 'required|exists:utility_rates,id',
            'water_meter_image_start' => 'nullable|image|max:2048',
            'water_meter_image_end' => 'nullable|image|max:2048',
            'electric_meter_image_start' => 'nullable|image|max:2048',
            'electric_meter_image_end' => 'nullable|image|max:2048',
            'notes' => 'nullable|string|max:1000',
        ], $messages);


        try {
            DB::beginTransaction();

            // Handle file uploads
            if ($request->hasFile('water_meter_image_start')) {
                $validated['water_meter_image_start'] = $request->file('water_meter_image_start')->store('meter-readings', 'public');
            }
            if ($request->hasFile('water_meter_image_end')) {
                $validated['water_meter_image_end'] = $request->file('water_meter_image_end')->store('meter-readings', 'public');
            }
            if ($request->hasFile('electric_meter_image_start')) {
                $validated['electric_meter_image_start'] = $request->file('electric_meter_image_start')->store('meter-readings', 'public');
            }
            if ($request->hasFile('electric_meter_image_end')) {
                $validated['electric_meter_image_end'] = $request->file('electric_meter_image_end')->store('meter-readings', 'public');
            }

            // Set billing month from reading date
            $readingDate = Carbon::parse($validated['reading_date']);
            
            // Create the utility usage record
            $utilityUsage = UtilityUsage::create([
                'rental_id' => $validated['rental_id'],
                'reading_date' => $validated['reading_date'],
                'billing_month' => $readingDate->format('Y-m'),
                'water_meter_start' => $validated['water_meter_start'],
                'water_meter_end' => $validated['water_meter_end'],
                'electric_meter_start' => $validated['electric_meter_start'],
                'electric_meter_end' => $validated['electric_meter_end'],
                'utility_rate_id' => $validated['utility_rate_id'],
                'notes' => $validated['notes'] ?? null,
                'water_meter_image_start' => $validated['water_meter_image_start'] ?? null,
                'water_meter_image_end' => $validated['water_meter_image_end'] ?? null,
                'electric_meter_image_start' => $validated['electric_meter_image_start'] ?? null,
                'electric_meter_image_end' => $validated['electric_meter_image_end'] ?? null
            ]);

            // Get rental and rate information
            $rental = Rental::with('room')->findOrFail($validated['rental_id']);
            $utilityRate = UtilityRate::findOrFail($validated['utility_rate_id']);
            
            // Calculate charges
            $waterUsage = $validated['water_meter_end'] - $validated['water_meter_start'];
            $electricUsage = $validated['electric_meter_end'] - $validated['electric_meter_start'];
            
            $waterAmount = $waterUsage * $utilityRate->water_rate;
            $electricAmount = $electricUsage * $utilityRate->electric_rate;

            // Calculate the due date (10 days from reading date)
            $dueDate = $readingDate->copy()->addDays(10);
            
            // Create invoice with all required fields
            $invoice = Invoice::create([
                'rental_id' => $validated['rental_id'],
                'utility_usage_id' => $utilityUsage->id,
                'billing_date' => $readingDate,
                'due_date' => $dueDate,
                'rent_amount' => $rental->room->monthly_rent ?? 0,
                'other_charges' => $waterAmount + $electricAmount,
                'other_charges_notes' => "Water Usage: {$waterUsage}m³ at ₱{$utilityRate->water_rate}/m³ = ₱{$waterAmount}, Electric Usage: {$electricUsage}kWh at ₱{$utilityRate->electric_rate}/kWh = ₱{$electricAmount}",
                'total_amount' => ($rental->room->monthly_rent ?? 0) + $waterAmount + $electricAmount,
                'amount_paid' => 0,
                'balance' => ($rental->room->monthly_rent ?? 0) + $waterAmount + $electricAmount,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null
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
        $utilityUsage->load(['rental.room', 'rental.tenant', 'utilityRate']);
        $utilityRates = UtilityRate::orderBy('effective_date', 'desc')->get();
        return view('admin.utility-usages.edit', compact('utilityUsage', 'utilityRates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UtilityUsage $utilityUsage)
    {
        $validated = $request->validate([
            'reading_date' => 'required|date',
            'water_meter_start' => 'required|numeric|min:0',
            'water_meter_end' => 'required|numeric|min:0|gt:water_meter_start',
            'electric_meter_start' => 'required|numeric|min:0',
            'electric_meter_end' => 'required|numeric|min:0|gt:electric_meter_start',
            'utility_rate_id' => 'required|exists:utility_rates,id',
            'water_meter_image_start' => 'nullable|image|max:2048',
            'water_meter_image_end' => 'nullable|image|max:2048',
            'electric_meter_image_start' => 'nullable|image|max:2048',
            'electric_meter_image_end' => 'nullable|image|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Handle file uploads
            if ($request->hasFile('water_meter_image_start')) {
                if ($utilityUsage->water_meter_image_start) {
                    Storage::disk('public')->delete($utilityUsage->water_meter_image_start);
                }
                $validated['water_meter_image_start'] = $request->file('water_meter_image_start')->store('meter-readings', 'public');
            }

            if ($request->hasFile('water_meter_image_end')) {
                if ($utilityUsage->water_meter_image_end) {
                    Storage::disk('public')->delete($utilityUsage->water_meter_image_end);
                }
                $validated['water_meter_image_end'] = $request->file('water_meter_image_end')->store('meter-readings', 'public');
            }

            if ($request->hasFile('electric_meter_image_start')) {
                if ($utilityUsage->electric_meter_image_start) {
                    Storage::disk('public')->delete($utilityUsage->electric_meter_image_start);
                }
                $validated['electric_meter_image_start'] = $request->file('electric_meter_image_start')->store('meter-readings', 'public');
            }

            if ($request->hasFile('electric_meter_image_end')) {
                if ($utilityUsage->electric_meter_image_end) {
                    Storage::disk('public')->delete($utilityUsage->electric_meter_image_end);
                }
                $validated['electric_meter_image_end'] = $request->file('electric_meter_image_end')->store('meter-readings', 'public');
            }

            $utilityUsage->update($validated);
            
            DB::commit();
            
            return redirect()->route('utility-usages.index')
                ->with('success', 'Utility usage updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update utility usage: ' . $e->getMessage());
        }

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

