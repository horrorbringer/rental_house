<!DOCTYPE html>
<html lang="km">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
            margin: 12px;
        }
        * {
            font-family: 'Hanuman' !important;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Hanuman' !important;
            font-size: 1rem;
            line-height: 1.4;
            margin-left: 40px;
            margin-right: 40px;
            color: #000;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .header {
            position: relative;
            text-align: center;
            margin-bottom: 8px;
            height: 50px;
        }
        .header-title{
            font-size: 3rem;
            font-weight: bold;
        }
        .td-logo {
            width: 20%;
            border: none;
            padding: 0;
            background: rgb(60, 135, 209)
        }
        .td-title {
            width: 80%;
            border: none;
            padding: 0;
            text-align: center;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 50%;
            margin-top: -25px; /* Half of logo height */
            width: 80px;
            height: auto;
        }
        h2 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            line-height: 50px; /* Same as header height */
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 6px;
        }
        td, th {
            padding: 7px;
            border: 1px solid #000;
            line-height: 1.3;
        }
        .no-border td {
            border: none;
            padding: 4px 4px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .col4 {
            width: 40%;
            border: none;
        }
        th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .row-header{
            margin: 20 0;
        }
    </style>
</head>
<body>
    <table class="row-header">
        <tr >
            <td class="td-logo">
                <div class="logo-container">
                    <h2>Logo</h2>
                </div>
            </td>
            <td class="td-title">
                <h2 class="header-title">បង្កាន់ដៃបង់ប្រាក់</h2>
            </td>
        </tr>
    </table>

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
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">{{ $invoice->utilityUsage->water_usage }}</td>
                <td class="text-right">${{ number_format($invoice->rental->room->water_fee, 2) }}</td>
                <td class="text-right">${{ number_format($invoice->total_water_fee, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">៣</td>
                <td>ថ្លៃភ្លើង</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">{{ $invoice->utilityUsage->electric_usage }}</td>
                <td class="text-right">${{ number_format($invoice->rental->room->electric_fee, 2) }}</td>
                <td class="text-right">${{ number_format($invoice->total_electric_fee, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="col4"></td>
                <td class="text-right" colspan="2"><strong>សរុប</strong></td>
                <td class="text-right">${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
