<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Order;

class InvoiceController extends Controller
{
    public function download($order_id)
    {
    $order = Order::with('items.produk')->findOrFail($order_id);

    $pdf = PDF::loadView('user.orders.invoice-pdf', compact('order'))
        ->setPaper('A4', 'portrait');

    $fileName = "Invoice-" . $order->order_code . ".pdf";

    return $pdf->download($fileName);
    }
}
