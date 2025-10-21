<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $messageText; // <-- nama property jelas, bukan 'message'

    public function __construct($order, $messageText)
    {
        $this->order = $order;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject('🧾 Pesanan #' . $this->order->id . ' — Batik Wistara')
                    ->view('emails.order_status')
                    ->with([
                        'order' => $this->order,
                        // KIRIM dengan key 'textMessage' (harus match di Blade)
                        'textMessage' => $this->messageText,
                    ]);
    }
}
