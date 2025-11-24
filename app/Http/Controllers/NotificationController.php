<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send notification automatically (no manual click needed)
     */
    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
            'media_url' => 'nullable|url'
        ]);

        $result = $this->whatsappService->sendMessage(
            $validated['phone'],
            $validated['message'],
            $validated['media_url'] ?? null
        );

        return response()->json($result);
    }

    /**
     * Example: Auto-send after order created
     */
    public function sendOrderConfirmation($orderId)
    {
        // Get order details from database
        // $order = Order::find($orderId);

        $phone = '+6281234567890'; // from order data
        $message = "Pesanan Anda #$orderId telah dikonfirmasi. Terima kasih!";

        $this->whatsappService->sendMessage($phone, $message);
    }
}
