<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #333; line-height: 1.6; font-size: 14px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .05); }
        table { width: 100%; border-collapse: collapse; }
        .header-table td { padding: 5px; vertical-align: middle; }
        .header-table .logo-container { width: 200px; }
        .header-table .logo-container img { max-width: 180px; max-height: 100px; }
        .header-table .invoice-title { text-align: right; }
        .invoice-title h1 { margin: 0; font-size: 45px; color: #333; }
        .address-table { margin-top: 40px; margin-bottom: 40px; }
        .address-table td { width: 50%; padding: 0 5px; vertical-align: top; }
        .item-table { text-align: left; }
        .item-table thead th { background: #f7f7f7; border-bottom: 2px solid #ddd; padding: 10px; font-weight: bold; }
        .item-table tbody td { padding: 10px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .totals-section { margin-top: 20px; }
        .totals-table { width: 40%; margin-left: 60%; }
        .totals-table td { padding: 8px; }
        .totals-table .label { font-weight: bold; color: #555; }
        .totals-table .grand-total td { font-size: 1.3em; font-weight: bold; border-top: 2px solid #333; border-bottom: 2px solid #333; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td class="logo-container">
                    {{-- Instructions: Place your logo in public/img/logo.png --}}
                    @if(file_exists(public_path('img/logo.png')))
                        <img src="{{ public_path('img/logo.png') }}">
                    @else
                        <h2 style="margin: 0;">{{ $settings['store_name'] ?? 'Laundry POS' }}</h2>
                    @endif
                </td>
                <td class="invoice-title">
                    <h1>INVOICE</h1>
                </td>
            </tr>
        </table>

        <table class="address-table">
            <tr>
                <td>
                    <strong>From:</strong><br>
                    {{ $settings['store_name'] ?? 'Your Company' }}<br>
                    {!! nl2br(e($settings['store_address'] ?? '123 Your Street, City')) !!}<br>
                    {{ $settings['store_phone'] ?? '' }}
                </td>
                <td class="text-right">
                    <strong>Bill To:</strong><br>
                    {{ $order->customer?->name }}<br>
                    {!! nl2br(e($order->customer?->address)) !!}<br>
                    {{ $order->customer?->phone }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="text-right">
                    <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}
                </td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th>Service / Item</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td class="text-right">{{ $service->pivot->quantity }}</td>
                    <td class="text-right">&#8377;{{ number_format($service->pivot->unit_price, 2) }}</td>
                    <td class="text-right">&#8377;{{ number_format($service->pivot->unit_price * $service->pivot->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="text-right">&#8377;{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                @if($order->service_charge_amount > 0)
                <tr>
                    <td class="label">Service Charge</td>
                    <td class="text-right">&#8377;{{ number_format($order->service_charge_amount, 2) }}</td>
                </tr>
                @endif
                @if($order->tax_amount > 0)
                <tr>
                    <td class="label">GST ({{ (int)$order->tax_rate }}%)</td>
                    <td class="text-right">&#8377;{{ number_format($order->tax_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="label">Grand Total</td>
                    <td class="text-right">&#8377;{{ number_format($order->grand_total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>