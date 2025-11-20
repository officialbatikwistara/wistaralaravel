<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\NewOrderMail;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $user)
    {
        $this->order = $order;
        $this->user  = $user;
    }

    /**
     * Delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Email notification using MailMessage wrapper for Mailable.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Menggunakan template dari Mailable (NewOrderMail)
        return (new MailMessage)
            ->subject('Pesanan Baru Masuk')
            ->view(
                'emails.new_order', // Blade file dari NewOrderMail
                [
                    'order' => $this->order,
                    'user'  => $this->user,
                    'admin' => $notifiable
                ]
            );
    }

    /**
     * Payload for database notifications.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message'     => "Pesanan baru #{$this->order->id} dari {$this->user->name}",
            'order_id'    => $this->order->id,
            'user_name'   => $this->user->name,
            'total'       => $this->order->total,
            'url'         => '/admin/pesanan/' . $this->order->id,
            'type'        => 'new_order'
        ];
    }
}
