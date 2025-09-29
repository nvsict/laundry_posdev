<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Generate the full-page PDF invoice.
     */
    public function generateInvoice(Order $order)
    {
        $order->load('customer', 'services');
        $settings = Setting::all()->pluck('value', 'key')->all();
        $pdf = Pdf::loadView('invoice', compact('order', 'settings'));
        return $pdf->stream('invoice-'.$order->order_number.'.pdf');
    }

    /**
     * Generate the small thermal-style PDF receipt.
     */
    public function generateReceipt(Order $order)
    {
        $order->load('customer', 'services');
        $settings = Setting::all()->pluck('value', 'key')->all();
        $pdf = Pdf::loadView('receipt', compact('order', 'settings'));
        $pdf->setPaper([0, 0, 280, 841], 'portrait');
        return $pdf->stream('receipt-'.$order->order_number.'.pdf');
    }
}