<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['invoice.rental.tenant'])->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Invoice $invoice)
    {
        $invoice->load(['rental.tenant']);
        return view('admin.payments.create', compact('invoice'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request, Invoice $invoice)
    {
        try {
            DB::beginTransaction();

            // Get validated data with payment number and file handling
            $validated = $request->validated();

            // Create the payment
            $payment = $invoice->payments()->create($validated);

            // Calculate new balance
            $totalPaid = $invoice->payments()->sum('amount');
            $newBalance = $invoice->total_amount - $totalPaid;

            // Update invoice status and balance
            $invoice->update([
                'balance' => $newBalance,
                'status' => $newBalance <= 0 ? 'paid' : 'pending',
                'amount_paid' => $totalPaid
            ]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Payment of ₱' . number_format($validated['amount'], 2) . ' recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if exists
            if (isset($validated['payment_proof'])) {
                Storage::disk('public')->delete($validated['payment_proof']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to record payment. Please try again.');
        }
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
