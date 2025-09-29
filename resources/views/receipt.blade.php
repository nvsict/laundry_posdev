<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $order->order_number }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', 'Courier New', monospace; /* Use monospaced font */
            font-size: 12px; 
            line-height: 1.4; 
            color: #000; 
            width: 280px; /* Strict 80mm width */
        }
        .receipt-box { padding: 5px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 1px 0; }
        .text-right { text-align: right; }
        .totals-table td { padding-top: 5px; }
        .grand-total { border-top: 1px solid #000; font-weight: bold; }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="center bold">{{ $settings['store_name'] ?? 'Laundry POS' }}</div>
        <div class="center">
            {!! nl2br(e($settings['store_address'] ?? '')) !!}<br>
            {{ $settings['store_phone'] ?? '' }}
        </div>
        <div class="line"></div>
        <div>Receipt: {{ $order->order_number }}</div>
        <div>Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}</div>
        <div>Customer: {{ $order->customer->name ?? 'N/A' }}</div>
        <div class="line"></div>
        <table>
            <tr class="bold">
                <td>Item</td>
                <td class="text-right">Qty</td>
                <td class="text-right">Total</td>
            </tr>
            @foreach($order->services as $service)
            <tr>
                <td colspan="3">{{ $service->name }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-right">{{ $service->pivot->quantity }} x {{ number_format($service->pivot->unit_price, 2) }}</td>
                <td class="text-right">&#8377;{{ number_format($service->pivot->unit_price * $service->pivot->quantity, 2) }}</td>
            </tr>
            @endforeach
        </table>
        <div class="line"></div>
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">&#8377;{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->service_charge_amount > 0)
            <tr>
                <td>Service Charge:</td>
                <td class="text-right">&#8377;{{ number_format($order->service_charge_amount, 2) }}</td>
            </tr>
            @endif
            @if($order->tax_amount > 0)
            <tr>
                <td>GST ({{ (int)$order->tax_rate }}%):</td>
                <td class="text-right">&#8377;{{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td>GRAND TOTAL:</td>
                <td class="text-right">&#8377;{{ number_format($order->grand_total, 2) }}</td>
            </tr>
        </table>
        <div class="line"></div>
        <div class="center">
            Thank you for your business!
        </div>
    </div>
</body>
</html>