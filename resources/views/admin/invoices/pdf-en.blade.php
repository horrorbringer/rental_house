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
            text-align: center;
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
        .td, .th {
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
        .row-header{;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        /* .bg-box{
            background: #554b4b;
        } */
    </style>
</head>
<body>
    <table class="row-header" border="0">
        <tr border="0">
            <td class="td-logo">
                <div class="logo-container">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" width="120">
                </div>
            </td>
            <td class="td-title">
                <h2 class="header-title">PAYMENT RECEIPT</h2>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span style="margin-right: 40px">Date: {{ $invoice->billing_date->format('d') }}</span>
                <span style="margin-right: 40px">Month:  {{ $invoice->billing_date->format('m') }}</span>
                <span>Year: {{ $invoice->billing_date->format('Y') }}</span>
            </td>
        </tr>
        <tr border="0">
            <td colspan="2">
                <span style="margin-right: 60px;">Name: {{ $invoice->rental->tenant->name ?? '' }}</span>
                <span style="margin-right: 60px;">Phone Number: {{ $invoice->rental->tenant->phone ?? '' }}</span>
                <span >Room Number: {{ $invoice->rental->room->room_number ?? '' }}</span>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr class="text-center ">
                <th class="th">No.</th>
                <th class="th">Description</th>
                <th class="th">Previous</th>
                <th class="th">Current</th>
                <th class="th">Usage (KWh/m3)</th>
                <th class="th">Unit Price</th>
                <th class="th">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center td">1</td>
                <td class="td">Room rents</td>
                <td class="text-center td">-</td>
                <td class="text-center td">-</td>
                <td class="text-center td">-</td>
                <td class="text-center td">-</td>
                <td class="text-right td">៛{{ number_format($invoice->rent_amount, 2) }}</td>
            </tr>

            @if($invoice->utilityUsage)
            <tr>
                <td class="text-center td">2</td>
                <td class="td">Water Fee</td>
                <td class="text-center td">{{ $previousUsage ? number_format($previousUsage->water_usage, 2) : '0.00' }}</td>
                <td class="text-center td">{{ number_format($invoice->utilityUsage->water_usage, 2) }}</td>
                <td class="text-center td">{{ number_format($invoice->utilityUsage->water_usage - ($previousUsage ? $previousUsage->water_usage : 0), 2) }}</td>
                <td class="text-right td">៛{{ number_format($invoice->rental->room->water_fee, 2) }}</td>
                <td class="text-right td">៛{{ number_format($invoice->total_water_fee, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center td">3</td>
                <td class="td">Electric Fee</td>
                <td class="text-center td">{{ $previousUsage ? number_format($previousUsage->electric_usage, 2) : '0.00' }}</td>
                <td class="text-center td">{{ number_format($invoice->utilityUsage->electric_usage, 2) }}</td>
                <td class="text-center td">{{ number_format($invoice->utilityUsage->electric_usage - ($previousUsage ? $previousUsage->electric_usage : 0), 2) }}</td>
                <td class="text-right td">៛{{ number_format($invoice->rental->room->electric_fee, 2) }}</td>
                <td class="text-right td">៛{{ number_format($invoice->total_electric_fee, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="col4 td"></td>
                <td class="text-right td" colspan="2" style="text-align: center;"><strong>Total for bill</strong></td>
                <td class="text-right td"><strong>៛{{ number_format($invoice->total_amount, 2) }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>

    <table style="margin-top: 20px;">
        <tr>
            <td class="bg-box" align="left" width="200px">
                <div>
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/qr-example.png'))) }}" width="200px">
                </div>
            </td>
            <td class="bg-box" align="left" width="200px">
                <div>
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/qr-example.png'))) }}" width="200px">
                </div>
            </td>
            <td class="bg-box" align="right">
                <div>
                    <span>Customer's Signature</span>
                </div>
                <div style="margin-top: 100px;"> 
                    <span>- - - - - - - - - - - -</span>
                </div>
            </td>
            <td class="bg-box" align="right" width="25%">
                <div>
                    <span>Cashier's Signature</span>
                </div>
                <div style="margin-top: 100px;">
                    <span>- - - - - - - - - - - -</span>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
