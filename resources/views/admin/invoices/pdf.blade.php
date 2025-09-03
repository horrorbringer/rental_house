<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <title>បង្កាន់ដៃបង់ប្រាក់</title>
    <style>
        @font-face {
            font-family: 'Hanuman';
            src: url('{{ storage_path('fonts/Hanuman-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Hanuman';
            src: url('{{ storage_path('fonts/Hanuman-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @page {
            size: A5 landscape;
            margin: 15px;
        }
        body {
            font-family: 'Hanuman', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
        }
        .header {
            text-align: center;
            position: relative;
            margin-bottom: 10px;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 120px;
        }
        h2 {
            font-size: 30px;
            margin: 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            padding: 3px;
            border: 1px solid #000;
        }
        .no-border td {
            border: none;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 25px;
            text-align: center;
        }
        .qr {
            width: 80px;
            height: 80px;
        }
        .col4 {
            width: 40%;
            border: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/jpeg;base64,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" class="logo" alt="Logo">
        <h2>បង្កាន់ដៃបង់ប្រាក់</h2>
    </div>

    <table class="no-border" style="margin-bottom: 10px;">
        <tr>
            <td style="width: 15%;">ឈ្មោះ</td>
            <td style="width: 35%;">{{ $invoice->rental->tenant->name ?? '' }}</td>
            <td style="width: 10%;">ថ្ងៃទី</td>
            <td style="width: 10%;">{{ $invoice->billing_date->format('d') }}</td>
            <td style="width: 10%;">ខែ</td>
            <td style="width: 10%;">{{ $invoice->billing_date->format('m') }}</td>
            <td style="width: 10%;">ឆ្នាំ {{ $invoice->billing_date->format('Y') }}</td>
        </tr>
        <tr>
            <td>លេខបន្ទប់</td>
            <td>{{ $invoice->rental->room->room_number ?? '' }}</td>
            <td>អគារ</td>
            <td colspan="4">{{ $invoice->rental->room->building->name ?? '' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr class="text-center">
                <th>ល.រ</th>
                <th>បរិយាយ</th>
                <th>លេខកុងទ័រចាស់</th>
                <th>លេខកុងទ័រថ្មី</th>
                <th>ចំនួន</th>
                <th>តម្លៃរាយ</th>
                <th>តម្លៃសរុប</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">១</td>
                <td>ថ្លៃបន្ទប់</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-right">-</td>
                <td class="text-right">${{ number_format($invoice->rent_amount, 2) }}</td>
            </tr>

            @if($invoice->utilityUsage)
            <tr>
                <td class="text-center">២</td>
                <td>ថ្លៃទឹក</td>
                <td class="text-center">{{ $invoice->utilityUsage->water_meter_start }}</td>
                <td class="text-center">{{ $invoice->utilityUsage->water_meter_end }}</td>
                <td class="text-center">{{ $invoice->utilityUsage->water_meter_end - $invoice->utilityUsage->water_meter_start }}</td>
                <td class="text-right">${{ number_format($invoice->utilityUsage->utilityRate->water_rate, 2) }}</td>
                <td class="text-right">${{ number_format(($invoice->utilityUsage->water_meter_end - $invoice->utilityUsage->water_meter_start) * $invoice->utilityUsage->utilityRate->water_rate, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">៣</td>
                <td>ថ្លៃភ្លើង</td>
                <td class="text-center">{{ $invoice->utilityUsage->electric_meter_start }}</td>
                <td class="text-center">{{ $invoice->utilityUsage->electric_meter_end }}</td>
                <td class="text-center">{{ $invoice->utilityUsage->electric_meter_end - $invoice->utilityUsage->electric_meter_start }}</td>
                <td class="text-right">${{ number_format($invoice->utilityUsage->utilityRate->electric_rate, 2) }}</td>
                <td class="text-right">${{ number_format(($invoice->utilityUsage->electric_meter_end - $invoice->utilityUsage->electric_meter_start) * $invoice->utilityUsage->utilityRate->electric_rate, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="col4"></td>
                <td class="text-right" colspan="2"><strong>សរុប</strong></td>
                <td class="text-right">${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <table class="no-border" style="width: 100%;">
            <tr>
                <td style="width: 50%; text-center">
                    <img src="https://via.placeholder.com/80x80?text=QR1" class="qr"><br>
                    <p>ហត្ថលេខាអ្នកទទួល</p>
                </td>
                <td style="width: 50%; text-center">
                    <img src="https://via.placeholder.com/80x80?text=QR2" class="qr"><br>
                    <p>ហត្ថលេខាអ្នកបង់</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
