<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['invoice.rental.tenant'])->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $invoices = Invoice::whereIn('status', ['pending', 'overdue'])
            ->with(['rental.tenant'])
            ->get();
        return view('payments.create', compact('invoices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $validated = $request->validated();
        $invoice = Invoice::findOrFail($validated['invoice_id']);

        $payment = DB::transaction(function () use ($validated, $invoice) {
            // Create the payment
            $payment = Payment::create($validated);

            // Update invoice status if fully paid
            $invoice->fresh();
            if ($invoice->balance <= 0) {
                $invoice->update(['status' => 'paid']);
            } elseif ($invoice->status === 'overdue') {
                $invoice->update(['status' => 'pending']);
            }

            return $payment;
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['invoice.rental.tenant']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        if ($payment->created_at->diffInDays(now()) > 7) {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Payments older than 7 days cannot be edited.');
        }

        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        if ($payment->created_at->diffInDays(now()) > 7) {
            return back()->with('error', 'Payments older than 7 days cannot be edited.');
        }

        $validated = $request->validated();
        $payment->update($validated);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        if ($payment->created_at->diffInDays(now()) > 7) {
            return back()->with('error', 'Payments older than 7 days cannot be deleted.');
        }

        $invoice = $payment->invoice;

        DB::transaction(function () use ($payment, $invoice) {
            $payment->delete();

            // Update invoice status if needed
            if ($invoice->fresh()->balance > 0) {
                $invoice->update(['status' => 'pending']);
            }
        });

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
