<!DOCTYPE html>
<html lang="km">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>បង្កាន់ដៃបង់ប្រាក់</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body {
            font-size: 12px;
            line-height: 1.4;
            margin-left: 40px;
            margin-right: 40px;
            color: #000;
        }
        .header-title{
            font-size: 3rem;
            font-weight: bold;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 6px;
        }
        .td,.th {
            padding: 8px 3px; /* more breathing room */
            border: 1px solid #000;
            line-height: 1.4;
        }
        .no-border{
            border: none;
            padding: 6px 0px;
        }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        th { background-color: #f8f8f8; font-weight: bold; }
        .col4 { width: 40%; border: none; }

        .date-row {
            text-align: left;
            font-size: 0.9rem;
            padding: 20px 0 15px 0;
        }
        .info-row {
            font-size: 0.9rem;
            padding-bottom: 20px;
        }
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
            <td colspan="2" class="td-title">
                <h2 class="header-title">បង្កាន់ដៃបង់ប្រាក់</h2>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="date-row">
                <span style="margin-right:40px">កាលបរិច្ឆេទ: {{ $invoice->billing_date->format('d') }}</span>
                <span style="margin-right:40px">ខែ: {{ $invoice->billing_date->format('m') }}</span>
                <span>ឆ្នាំ: {{ $invoice->billing_date->format('Y') }}</span>
            </td>
        </tr>
        <tr >
            <td style="border:0; width:33%;">ឈ្មោះអតិថិជន: {{ $invoice->rental->tenant->name ?? '' }}</td>
            <td style="border:0; width:33%;">លេខទូរស័ព្ទ: {{ $invoice->rental->tenant->phone ?? '' }}</td>
            <td style="border:0; width:34%;">លេខបន្ទប់: {{ $invoice->rental->room->room_number ?? '' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr class="text-center">
                <th class="th">ល.រ</th>
                <th class="th">បរិយាយ</th>
                <th class="th">មុន</th>
                <th class="th">បច្ចុប្បន្ន</th>
                <th class="th">បរិមាណ</th>
                <th class="th">តម្លៃឯកតា</th>
                <th class="th">សរុប</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center td">1</td>
                <td class="td">ថ្លៃជួលបន្ទប់</td>
                <td class="text-center td">-</td>
                <td class="text-center td">-</td>
                <td class="text-center td">-</td>
                <td class="text-center td">-</td>
                <td class="text-right td">៛{{ number_format($invoice->rent_amount, 2) }}</td>
            </tr>

            @if($invoice->utilityUsage)
            <tr>
                <td class="text-center td">2</td>
                <td class="td">ថ្លៃទឹក</td>
                <td class="text-center td">{{ $previousUsage ? number_format($previousUsage->water_usage, 2) : '0.00' }} គូប</td>
                <td class="text-center td">{{ number_format($invoice->utilityUsage->water_usage, 2) }} គូប</td>
                <td class="text-center td">{{ number_format($invoice->utilityUsage->water_usage - ($previousUsage ? $previousUsage->water_usage : 0), 2) }} គូប</td>
                <td class="text-right td">៛{{ number_format($invoice->rental->room->water_fee, 2) }}</td>
                <td class="text-right td">៛{{ number_format($invoice->total_water_fee, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center td">3</td>
                <td class="td">ថ្លៃអគ្គិសនី</td>
                <td class="text-center td">{{ $previousUsage ? number_format($previousUsage->electric_usage, 2) : '0.00' }} គីឡូវ៉ាត់</td>
                <td class="text-center td">{{ number_format($invoice->utilityUsage->electric_usage, 2) }} គីឡូវ៉ាត់</td>
                <td class="text-center td">{{ number_format($invoice->utilityUsage->electric_usage - ($previousUsage ? $previousUsage->electric_usage : 0), 2) }} គីឡូវ៉ាត់</td>
                <td class="text-right td">៛{{ number_format($invoice->rental->room->electric_fee, 2) }}</td>
                <td class="text-right td">៛{{ number_format($invoice->total_electric_fee, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="col4 no-border"></td>
                <td class="text-right td" colspan="2" style="text-align:center;"><strong>សរុបបង់</strong></td>
                <td class="text-right td"><strong>៛{{ number_format($invoice->total_amount, 2) }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>

    <table style="margin-top:10px; width:100%; border:0;">
    <tr>
        <!-- QR Codes -->
        <td style="border:0; width:40%; text-align:left;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/qr-example.png'))) }}" width="120px">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/qr-example.png'))) }}" width="120px">
        </td>

        <!-- Customer Signature -->
        <td style="border:0; width:30%; text-align:center; padding-bottom:80px;">
            <div>ហត្ថលេខាអ្នកប្រគល់ប្រាក់</div>
        </br>
    </br>
            <div style="margin-top:60px;">......................</div>
        </td>


        <!-- Cashier Signature -->
        <td style="border:0; width:30%; text-align:center; padding-bottom:80px;">
            <div>ហត្ថលេខាអ្នកទទួលប្រាក់</div>
            <div>......................</div>
        </td>
    </tr>
    </table>

</body>
</html>
