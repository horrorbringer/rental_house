<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }
            .page {
                width: 100%;
                height: 100%;
            }
        }
        .logo {
            width: 180px;
            height: 80px;
        }
    </style>
</head>
<body class="bg-white p-4">
    <div class="max-w-7xl mx-auto page">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div class="">    
                <img src="{{asset('images/logo.png')}}" alt="The Mongkol Residence" class="logo">
            </div>
            <div class="flex items-center">
                <div class="items-center">
                    <h1 class="text-3xl font-bold mt-4">វិក្កយបត្រ បង់ប្រាក់</h1>
                    <p class="text-lg">តារាងគិតថ្លៃឈ្នួលបន្ទប់ជួល</p>
                </div>
            </div>
            <div class="">
                <p>លេខបន្ទប់: {{ $invoice->rental->room->number }}</p>
                <p>ថ្ងៃទី {{ now()->format('d') }} ខែ {{ now()->format('m') }} ឆ្នាំ {{ now()->format('Y') }}</p>
            </div>
        </div>

        <!-- Tenant Info -->
        <div class="mb-4">
            <div class="grid grid-cols-4 gap-4">
                <p>ឈ្មោះអ្នកជួល: {{ $invoice->rental->tenant->name }}</p>
                <p>លេខទូរស័ព្ទ: {{ $invoice->rental->tenant->phone }}</p>
                <p>លេខបន្ទប់: {{ $invoice->rental->room->number }}</p>
                <p>សម្រាប់ខែ: {{ $invoice->billing_month->format('F Y') }}</p>
            </div>
        </div>

        <!-- Invoice Table -->
        <table class="w-full border-collapse border border-gray-800 mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-800 px-6 py-3 text-center w-16">ល.រ</th>
                    <th class="border border-gray-800 px-6 py-3 text-left">បរិយាយ</th>
                    <th class="border border-gray-800 px-6 py-3 text-center">លេខកុងទ័រចាស់</th>
                    <th class="border border-gray-800 px-6 py-3 text-center">លេខកុងទ័រថ្មី</th>
                    <th class="border border-gray-800 px-6 py-3 text-center">ចំនួន(គីឡូ/ម៉ែត្រ)</th>
                    <th class="border border-gray-800 px-6 py-3 text-right">តម្លៃ</th>
                    <th class="border border-gray-800 px-6 py-3 text-right w-32">តម្លៃសរុប</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-800 px-6 py-3 text-center">១</td>
                    <td class="border border-gray-800 px-6 py-3">ថ្លៃបន្ទប់</td>
                    <td class="border border-gray-800 px-6 py-3 text-center">-</td>
                    <td class="border border-gray-800 px-6 py-3 text-center">-</td>
                    <td class="border border-gray-800 px-6 py-3 text-center">-</td>
                    <td class="border border-gray-800 px-6 py-3 text-right">-</td>
                    <td class="border border-gray-800 px-6 py-3 text-right">${{ number_format($invoice->rent_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 px-6 py-3 text-center">២</td>
                    <td class="border border-gray-800 px-6 py-3">ថ្លៃទឹក</td>
                    <td class="border border-gray-800 px-6 py-3 text-center">-</td>
                    <td class="border border-gray-800 px-6 py-3 text-center">-</td>
                    <td class="border border-gray-800 px-6 py-3 text-center">{{ number_format($invoice->rental->utilityUsage->water_usage ?? 0, 2) }}</td>
                    <td class="border border-gray-800 px-6 py-3 text-right">${{ number_format($invoice->water_fee, 2) }}</td>
                    <td class="border border-gray-800 px-6 py-3 text-right">${{ number_format($invoice->water_usage_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 px-6 py-3 text-center">៣</td>
                    <td class="border border-gray-800 px-6 py-3">ថ្លៃភ្លើង</td>
                    <td class="border border-gray-800 px-6 py-3 text-center">-</td>
                    <td class="border border-gray-800 px-6 py-3 text-center">-</td>
                    <td class="border border-gray-800 px-6 py-3 text-center">{{ number_format($invoice->rental->utilityUsage->electric_usage ?? 0, 2) }}</td>
                    <td class="border border-gray-800 px-6 py-3 text-right">${{ number_format($invoice->electric_fee, 2) }}</td>
                    <td class="border border-gray-800 px-6 py-3 text-right">${{ number_format($invoice->electric_usage_amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-50">
                    <td colspan="6" class="border border-gray-800 px-6 py-3 text-right font-bold">ប្រាក់សរុបត្រូវបង់</td>
                    <td class="border border-gray-800 px-6 py-3 text-right font-bold">${{ number_format($invoice->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="grid grid-cols-3 gap-8 mt-6">
            <div>
                <p class="font-bold mb-2">សូមធ្វើការទូទាត់តាម:</p>
                <div class="flex items-center space-x-4">
                    <div id="qrcode" class="w-32 h-32"></div>
                    <div>
                        <p class="text-lg font-medium">ABA Pay</p>
                        <p class="text-lg">{{ $invoice->rental->tenant->phone }}</p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div class="h-32 flex items-end justify-center border-b border-gray-400">
                </div>
                <p class="mt-2">ហត្ថលេខាអ្នកគិតលុយ</p>
            </div>
            <div class="text-center">
                <div class="h-32 flex items-end justify-center border-b border-gray-400">
                </div>
                <p class="mt-2">ហត្ថលេខាអ្នកទទួលលុយ</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = () => {
            // Generate QR Code
            new QRCode(document.getElementById("qrcode"), {
                text: "{{ $invoice->rental->tenant->phone }}",
                width: 128,
                height: 128,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            // Wait a moment for QR code to generate before printing
            setTimeout(() => {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
