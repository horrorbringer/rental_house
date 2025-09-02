<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Rental;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['rental.room', 'rental.tenant'])->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rentals = Rental::with(['room', 'tenant'])->whereDoesntHave('invoices', function($query) {
            $query->whereMonth('billing_month', now()->month)
                  ->whereYear('billing_month', now()->year);
        })->get();
        
        return view('invoices.create', compact('rentals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'billing_month' => 'required|date',
            'rent_amount' => 'required|numeric|min:0',
            'water_fee' => 'required|numeric|min:0',
            'electric_fee' => 'required|numeric|min:0',
            'water_usage_amount' => 'required|numeric|min:0',
            'electric_usage_amount' => 'required|numeric|min:0',
        ]);

        $validated['total'] = $validated['rent_amount'] + 
                            $validated['water_fee'] + 
                            $validated['electric_fee'] +
                            $validated['water_usage_amount'] + 
                            $validated['electric_usage_amount'];

        $invoice = Invoice::create($validated);

        return redirect()->route('invoices.show', $invoice)
                        ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['rental.room', 'rental.tenant', 'utilityUsage.utilityRate', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Generate PDF for the specified invoice.
     */
    public function generatePdf(Invoice $invoice)
    {
        $invoice->load(['rental.room.building', 'rental.tenant', 'utilityUsage.utilityRate', 'payments']);
        
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = Invoice::with(['rental.room', 'rental.tenant'])->findOrFail($id);
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        $validated = $request->validate([
            'rent_amount' => 'required|numeric|min:0',
            'water_fee' => 'required|numeric|min:0',
            'electric_fee' => 'required|numeric|min:0',
            'water_usage_amount' => 'required|numeric|min:0',
            'electric_usage_amount' => 'required|numeric|min:0',
        ]);

        $validated['total'] = $validated['rent_amount'] + 
                            $validated['water_fee'] + 
                            $validated['electric_fee'] +
                            $validated['water_usage_amount'] + 
                            $validated['electric_usage_amount'];

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice)
                        ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')
                        ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Print the specified invoice.
     */
    public function print(string $id)
    {
        $invoice = Invoice::with(['rental.room', 'rental.tenant'])->findOrFail($id);
        return view('invoices.print', compact('invoice'));
    }
}