<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #1f2937;
        }
        .container {
            padding: 2rem;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header h1 {
            color: #4f46e5;
            font-size: 24px;
            margin: 0;
        }
        .invoice-details {
            margin-bottom: 2rem;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details td {
            padding: 0.5rem;
            vertical-align: top;
        }
        .invoice-details .label {
            color: #6b7280;
            width: 150px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .items-table th {
            background-color: #f3f4f6;
            padding: 0.75rem;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .totals {
            width: 100%;
            margin-top: 2rem;
        }
        .totals td {
            padding: 0.5rem;
        }
        .totals .label {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #e5e7eb;
        }
        .footer {
            margin-top: 3rem;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .utility-readings {
            margin: 1rem 0;
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 4px;
        }
        .payment-info {
            margin-top: 2rem;
            padding: 1rem;
            background-color: #f3f4f6;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>INVOICE</h1>
            <p>{{ config('app.name') }}</p>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <table>
                <tr>
                    <td class="label">Invoice Number:</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td class="label">Date:</td>
                    <td>{{ $invoice->billing_date->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Due Date:</td>
                    <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                    <td class="label">Status:</td>
                    <td>{{ ucfirst($invoice->status) }}</td>
                </tr>
            </table>
        </div>

        <!-- Tenant Details -->
        <div class="invoice-details">
            <table>
                <tr>
                    <td class="label">Tenant:</td>
                    <td>{{ $invoice->rental->tenant->name }}</td>
                </tr>
                <tr>
                    <td class="label">Room:</td>
                    <td>{{ $invoice->rental->room->name }} ({{ $invoice->rental->room->building->name }})</td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- Room Rent -->
                <tr>
                    <td>Room Rent</td>
                    <td style="text-align: right;">${{ number_format($invoice->rent_amount, 2) }}</td>
                </tr>

                <!-- Utility Usage -->
                @if($invoice->utilityUsage)
                    <tr>
                        <td colspan="2" style="padding: 0;">
                            <div class="utility-readings">
                                <strong>Water Usage:</strong><br>
                                Previous: {{ $invoice->utilityUsage->water_meter_start }} m³ |
                                Current: {{ $invoice->utilityUsage->water_meter_end }} m³ |
                                Used: {{ $invoice->utilityUsage->water_meter_end - $invoice->utilityUsage->water_meter_start }} m³ |
                                Rate: ${{ number_format($invoice->utilityUsage->utilityRate->water_rate, 2) }}/m³<br>
                                <strong>Electric Usage:</strong><br>
                                Previous: {{ $invoice->utilityUsage->electric_meter_start }} kWh |
                                Current: {{ $invoice->utilityUsage->electric_meter_end }} kWh |
                                Used: {{ $invoice->utilityUsage->electric_meter_end - $invoice->utilityUsage->electric_meter_start }} kWh |
                                Rate: ${{ number_format($invoice->utilityUsage->utilityRate->electric_rate, 2) }}/kWh
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Water Charges</td>
                        <td style="text-align: right;">${{ number_format($invoice->utilityUsage->water_charge, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Electric Charges</td>
                        <td style="text-align: right;">${{ number_format($invoice->utilityUsage->electric_charge, 2) }}</td>
                    </tr>
                @endif

                <!-- Other Charges -->
                @if($invoice->other_charges > 0)
                    <tr>
                        <td>
                            Other Charges
                            @if($invoice->other_charges_notes)
                                <br><small style="color: #6b7280;">{{ $invoice->other_charges_notes }}</small>
                            @endif
                        </td>
                        <td style="text-align: right;">${{ number_format($invoice->other_charges, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals">
            <tr>
                <td></td>
                <td class="label">Subtotal:</td>
                <td style="text-align: right;">${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="label">Amount Paid:</td>
                <td style="text-align: right;">${{ number_format($invoice->amount_paid, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td></td>
                <td class="label">Balance Due:</td>
                <td style="text-align: right;">${{ number_format($invoice->balance, 2) }}</td>
            </tr>
        </table>

        <!-- Payment Information -->
        <div class="payment-info">
            <strong>Payment Methods:</strong><br>
            Bank Transfer: [Your Bank Details]<br>
            QR Code Payment: [Your Payment QR Details]<br>
            <small>Please include your invoice number ({{ $invoice->invoice_number }}) as reference when making payment.</small>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>If you have any questions about this invoice, please contact us.</p>
            <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
