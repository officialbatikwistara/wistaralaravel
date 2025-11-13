<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;

    public function __construct($order, $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('🆕 Pesanan Baru #' . $this->order->id . ' — Batik Wistara')
                    ->view('emails.new_order')
                    ->with([
                        'order' => $this->order,
                        'user' => $this->user,
                    ]);
    }
}
