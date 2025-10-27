<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Mail\OrderStatusMail;

class OrderStatusNotification extends Notification
{
    public $order;
    public $messageText;

    public function __construct($order, $messageText)
    {
        $this->order = $order;
        $this->messageText = $messageText;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Kembalikan Mailable, JANGAN MailMessage
        return (new OrderStatusMail($this->order, $this->messageText))
            ->to($notifiable->email);
    }
}
