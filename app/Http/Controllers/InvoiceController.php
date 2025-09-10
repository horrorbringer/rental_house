<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Rental;
use App\Models\UtilityUsage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['rental.room.building', 'rental.tenant'])
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
                    'overdue' => $query->where('status', Invoice::STATUS_OVERDUE),
                    default => $query
                };
            })
            ->latest('billing_date');

        $invoices = $query->paginate(10)->withQueryString();

        // Calculate statistics
        $stats = [
            'total_pending' => DB::selectOne('SELECT SUM(total_amount) as total FROM invoices WHERE status = ?', [Invoice::STATUS_PENDING])->total ?? 0,
            'total_overdue' => DB::selectOne('SELECT SUM(total_amount) as total FROM invoices WHERE status = ?', [Invoice::STATUS_OVERDUE])->total ?? 0,
            'total_paid' => DB::selectOne('SELECT SUM(total_amount) as total FROM invoices WHERE status = ?', [Invoice::STATUS_PAID])->total ?? 0,
            'overdue_count' => DB::selectOne('SELECT COUNT(*) as count FROM invoices WHERE status = ?', [Invoice::STATUS_OVERDUE])->count ?? 0,
            'total_this_month' => DB::selectOne(
                'SELECT SUM(total_amount) as total FROM invoices WHERE MONTH(billing_date) = ? AND YEAR(billing_date) = ?',
                [now()->month, now()->year]
            )->total ?? 0
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['rental.room', 'rental.tenant', 'utilityUsage']);

        $previousUsage = UtilityUsage::where('rental_id', $invoice->rental_id)
            ->where('reading_date', '<', $invoice->utilityUsage->reading_date)
            ->orderBy('reading_date', 'desc')
            ->first();

        return view('admin.invoices.show', compact('invoice', 'previousUsage'));
    }

    /**
     * Generate PDF for the specified invoice.
     * 
     * @param Invoice $invoice
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function generatePdf(Invoice $invoice)
    {
        try {
            // Load relationships
            $invoice->load(['rental.room.building', 'rental.tenant', 'utilityUsage']);

            // Basic validations
            if (!$invoice->rental || !$invoice->rental->room || !$invoice->rental->tenant) {
                throw new \Exception('Required invoice relationships not found');
            }

            // Prepare fonts directory
            $fontDir = storage_path('fonts');
            if (!file_exists($fontDir)) {
                mkdir($fontDir, 0755, true);
            }

            // Delete font cache if exists
            $fontCache = $fontDir . '/dompdf_font_family_cache.php';
            if (file_exists($fontCache)) {
                unlink($fontCache);
            }

            // Configure DomPDF
            $config = [
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Hanuman',
                'fontDir' => $fontDir,
                'fontCache' => $fontDir,
                'isPhpEnabled' => true,
                'defaultMediaType' => 'print',
                'defaultPaperSize' => 'A5',
                'defaultPaperOrientation' => 'landscape',
                'dpi' => 150,
                'enable_unicode' => true,
                'font_height_ratio' => 0.9
            ];

            // Create PDF instance
            $pdf = PDF::setOptions($config)->loadView('admin.invoices.pdf', compact('invoice'));
            
            // Configure DomPDF instance
            $dompdf = $pdf->getDomPDF();
            $dompdf->set_option('enable_font_subsetting', true);
            $dompdf->set_option('pdf_backend', 'CPDF');
            $dompdf->set_option('enable_unicode', true);
            $dompdf->set_option('unicode_enabled', true);
            $dompdf->setPaper('A5', 'landscape');
            
            // Generate filename
            $filename = sprintf(
                'invoice-%s-%s.pdf',
                preg_replace('/[^A-Za-z0-9-]/', '', $invoice->invoice_number),
                $invoice->billing_date->format('Y-m-d')
            );

            // Stream the PDF directly to browser
            return $pdf->stream($filename);

        } catch (\Exception $e) {
            // Log the error with detailed context
            logger()->error('PDF Generation Failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return user-friendly error message
            $errorMessage = config('app.debug') 
                ? 'PDF Generation Failed: ' . $e->getMessage()
                : 'Unable to generate PDF. Please try again later.';

            return back()->with('error', $errorMessage);
        }
    
    }

    public function generatePdfEn(Invoice $invoice)
    {
        try {
            // Load relationships
            $invoice->load(['rental.room.building', 'rental.tenant', 'utilityUsage']);

            // Get previous utility usage for comparison
            $previousUsage = null;
            if ($invoice->utilityUsage) {
                $previousUsage = UtilityUsage::where('rental_id', $invoice->rental_id)
                    ->where('reading_date', '<', $invoice->utilityUsage->reading_date)
                    ->orderBy('reading_date', 'desc')
                    ->first();
            }

            // Basic validations
            if (!$invoice->rental || !$invoice->rental->room || !$invoice->rental->tenant) {
                throw new \Exception('Required invoice relationships not found');
            }

            // Prepare fonts directory
            $fontDir = storage_path('fonts');
            if (!file_exists($fontDir)) {
                mkdir($fontDir, 0755, true);
            }

            // Delete font cache if exists
            $fontCache = $fontDir . '/dompdf_font_family_cache.php';
            if (file_exists($fontCache)) {
                unlink($fontCache);
            }

            // Configure DomPDF
            $config = [
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'fontDir' => $fontDir,
                'fontCache' => $fontDir,
                'isPhpEnabled' => true,
                'defaultMediaType' => 'print',
                'defaultPaperSize' => 'A5',
                'defaultPaperOrientation' => 'landscape',
                'dpi' => 150,
                'enable_unicode' => true,
                'font_height_ratio' => 0.9
            ];

            // Create PDF instance
            $pdf = PDF::setOptions($config)->loadView('admin.invoices.pdf-en', compact('invoice', 'previousUsage'));
            
            // Configure DomPDF instance
            $dompdf = $pdf->getDomPDF();
            $dompdf->set_option('enable_font_subsetting', true);
            $dompdf->set_option('pdf_backend', 'CPDF');
            $dompdf->set_option('enable_unicode', true);
            $dompdf->set_option('unicode_enabled', true);
            $dompdf->setPaper('A5', 'landscape');
            
            // Generate filename
            $filename = sprintf(
                'invoice-%s-%s-en.pdf',
                preg_replace('/[^A-Za-z0-9-]/', '', $invoice->invoice_number),
                $invoice->billing_date->format('Y-m-d')
            );

            // Stream the PDF directly to browser
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            // Log the error with detailed context
            logger()->error('PDF Generation Failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Return user-friendly error message
            $errorMessage = config('app.debug')

                ? 'PDF Generation Failed: ' . $e->getMessage()
                : 'Unable to generate PDF. Please try again later.';
            return back()->with('error', $errorMessage);
        }
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
            'total_water_fee' => 'required|numeric|min:0',
            'total_electric_fee' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', Invoice::$statuses),
            'notes' => 'nullable|string|max:1000',
            'due_date' => 'required|date|after_or_equal:billing_date'
        ]);

        // Calculate total amount
        $validated['total_amount'] = $validated['rent_amount'] + 
                                   $validated['total_water_fee'] + 
                                   $validated['total_electric_fee'];

        try {
            DB::beginTransaction();

            $invoice->update($validated);

            // If status changed to paid, log the payment
            if ($validated['status'] === Invoice::STATUS_PAID && $invoice->getOriginal('status') !== Invoice::STATUS_PAID) {
                $invoice->payments()->create([
                    'amount' => $invoice->total_amount,
                    'payment_date' => now(),
                    'payment_method' => 'cash',
                    'notes' => 'Payment marked as completed from invoice edit'
                ]);
            }

            DB::commit();
            return redirect()->route('invoices.show', $invoice)
                           ->with('success', 'Invoice updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }
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