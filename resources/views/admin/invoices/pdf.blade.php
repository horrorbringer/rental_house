<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px;
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
        }
        .total-table {
            width: 40%;
            float: right;
            margin-bottom: 30px;
        }
        .total-table td {
            padding: 5px;
        }
        .total-table .total-row {
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .footer {
            clear: both;
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #666;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <div class="status status-{{ $invoice->status }}">
                {{ ucfirst($invoice->status) }}
            </div>
        </div>

        <div class="invoice-info">
            <table>
                <tr>
                    <td width="50%">
                        <strong>Bill To:</strong><br>
                        {{ $invoice->rental->tenant->name }}<br>
                        {{ $invoice->rental->room->building->name }}<br>
                        Room {{ $invoice->rental->room->room_number }}
                    </td>
                    <td width="50%" style="text-align: right">
                        <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                        <strong>Date:</strong> {{ $invoice->billing_date->format('F j, Y') }}<br>
                        <strong>Due Date:</strong> {{ $invoice->due_date->format('F j, Y') }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Monthly Rent</td>
                    <td>₱{{ number_format($invoice->rent_amount, 2) }}</td>
                </tr>
                @if($invoice->utilityUsage)
                    <tr>
                        <td>
                            Water Usage ({{ number_format($invoice->utilityUsage->water_meter_end - $invoice->utilityUsage->water_meter_start, 2) }} m³ @ ₱{{ number_format($invoice->utilityUsage->utilityRate->water_rate, 2) }}/m³)
                        </td>
                        <td>₱{{ number_format(($invoice->utilityUsage->water_meter_end - $invoice->utilityUsage->water_meter_start) * $invoice->utilityUsage->utilityRate->water_rate, 2) }}</td>
                    </tr>
                    <tr>
                        <td>
                            Electric Usage ({{ number_format($invoice->utilityUsage->electric_meter_end - $invoice->utilityUsage->electric_meter_start, 2) }} kWh @ ₱{{ number_format($invoice->utilityUsage->utilityRate->electric_rate, 2) }}/kWh)
                        </td>
                        <td>₱{{ number_format(($invoice->utilityUsage->electric_meter_end - $invoice->utilityUsage->electric_meter_start) * $invoice->utilityUsage->utilityRate->electric_rate, 2) }}</td>
                    </tr>
                @endif
                @if($invoice->other_charges > 0)
                    <tr>
                        <td>
                            Other Charges<br>
                            <small>{{ $invoice->other_charges_notes }}</small>
                        </td>
                        <td>₱{{ number_format($invoice->other_charges, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table class="total-table">
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right">₱{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Amount Paid:</td>
                <td style="text-align: right">₱{{ number_format($invoice->amount_paid, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Balance Due:</td>
                <td style="text-align: right">₱{{ number_format($invoice->balance, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Payment Methods:</p>
            <p>Cash, GCash, or Bank Transfer</p>
            <p>Please include your invoice number when making payments.</p>
            @if($invoice->notes)
                <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
            @endif
        </div>
    </div>
</body>
</html>
