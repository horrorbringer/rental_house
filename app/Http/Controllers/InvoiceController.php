<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Rental;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['rental.room.building', 'rental.tenant', 'payments'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('rental.tenant', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->date_range, function ($query, $range) {
                return match($range) {
                    'today' => $query->whereDate('billing_date', now()),
                    'this_week' => $query->whereBetween('billing_date', [now()->startOfWeek(), now()->endOfWeek()]),
                    'this_month' => $query->whereMonth('billing_date', now()->month)->whereYear('billing_date', now()->year),
                    'last_month' => $query->whereMonth('billing_date', now()->subMonth()->month)->whereYear('billing_date', now()->subMonth()->year),
                    default => $query
                };
            })
            ->latest('billing_date');

        // Calculate statistics
        $totalOutstanding = Invoice::where('status', '!=', 'paid')->sum('balance');
        $overdueCount = Invoice::where('status', 'overdue')->count();
        $paidThisMonth = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');
        
        // Calculate collection rate (paid amount vs total amount for current month)
        $thisMonthTotal = Invoice::whereMonth('billing_date', now()->month)
            ->whereYear('billing_date', now()->year)
            ->sum('total_amount');
        $collectionRate = $thisMonthTotal > 0 
            ? ($paidThisMonth / $thisMonthTotal) * 100 
            : 0;

        $invoices = $query->paginate(10)->withQueryString();

        return view('admin.invoices.index', compact(
            'invoices',
            'totalOutstanding',
            'overdueCount',
            'paidThisMonth',
            'collectionRate'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['rental.room', 'rental.tenant', 'utilityUsage.utilityRate', 'payments']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Generate PDF for the specified invoice.
     */
    public function generatePdf(Invoice $invoice)
    {
        $invoice->load(['rental.room.building', 'rental.tenant', 'utilityUsage.utilityRate', 'payments']);
        
        $pdf = PDF::loadView('admin.invoices.pdf', compact('invoice'));
        
        // Configure PDF to use Unicode fonts
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('font-family', 'Hanuman');
        
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = Invoice::with(['rental.room', 'rental.tenant'])->findOrFail($id);
        return view('admin.invoices.edit', compact('invoice'));
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
}