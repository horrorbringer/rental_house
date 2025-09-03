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

            // Lock the invoice for update to prevent race conditions
            $invoice = Invoice::where('id', $invoice->id)->lockForUpdate()->first();
            
            // Force refresh the balance before validation
            $totalPaid = $invoice->payments()->sum('amount');
            
            // Calculate current balance
            $currentBalance = $invoice->total_amount - $totalPaid;
            
            // Update invoice with current balance
            $invoice->forceFill([
                'amount_paid' => $totalPaid,
                'balance' => $currentBalance
            ]);
            
            // Get validated data with payment number and file handling
            $validated = $request->validated();

            // Generate payment number
            $latestPayment = Payment::latest()->first();
            $nextId = $latestPayment ? $latestPayment->id + 1 : 1;
            $validated['payment_number'] = 'PY' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            // Verify payment amount against current balance
            if ($validated['amount'] > ($invoice->total_amount - $totalPaid)) {
                throw new \Exception('Payment amount exceeds current balance');
            }

            // Create the payment
            $payment = $invoice->payments()->create($validated);

            // Recalculate final balance
            $finalPaid = $invoice->payments()->sum('amount');
            $newBalance = $invoice->total_amount - $finalPaid;

            // Update invoice status and balance
            $invoice->update([
                'balance' => $newBalance,
                'status' => $newBalance <= 0 ? 'paid' : 'pending',
                'amount_paid' => $finalPaid,
                'paid_at' => $newBalance <= 0 ? now() : null
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

            $errorMessage = $e->getMessage() === 'Payment amount exceeds current balance' 
                ? 'Payment amount exceeds the current invoice balance. Please refresh and try again.'
                : 'Failed to record payment. Please try again.';

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
        }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['invoice.rental.tenant']);
        return view('admin.payments.show', compact('payment'));
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

        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        if ($payment->created_at->diffInDays(now()) > 7) {
            return back()->with('error', 'Payments older than 7 days cannot be edited.');
        }

        try {
            DB::beginTransaction();

            $invoice = $payment->invoice()->lockForUpdate()->first();
            $oldAmount = $payment->amount;
            
            // Update payment
            $validated = $request->validated();
            $payment->update($validated);

            // Recalculate invoice totals
            $totalPaid = $invoice->payments()->sum('amount');
            $newBalance = $invoice->total_amount - $totalPaid;

            // Update invoice
            $invoice->update([
                'balance' => $newBalance,
                'amount_paid' => $totalPaid,
                'status' => $newBalance <= 0 ? 'paid' : 'pending',
                'paid_at' => $newBalance <= 0 ? now() : null
            ]);

            DB::commit();

            return redirect()->route('payments.show', $payment)
                ->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update payment. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        if ($payment->created_at->diffInDays(now()) > 7) {
            return back()->with('error', 'Payments older than 7 days cannot be deleted.');
        }

        try {
            DB::transaction(function () use ($payment) {
                $invoice = $payment->invoice()->lockForUpdate()->first();
                
                // If there's a payment proof, delete it
                if ($payment->payment_proof) {
                    Storage::disk('public')->delete($payment->payment_proof);
                }
                
                // Delete payment
                $payment->delete();

                // Recalculate invoice totals
                $totalPaid = $invoice->payments()->sum('amount');
                $newBalance = $invoice->total_amount - $totalPaid;

                // Update invoice
                $invoice->update([
                    'balance' => $newBalance,
                    'amount_paid' => $totalPaid,
                    'status' => $newBalance <= 0 ? 'paid' : 'pending',
                    'paid_at' => $newBalance <= 0 ? now() : null
                ]);
            });

            return redirect()->route('payments.index')
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete payment. Please try again.');
        }
    }
}
