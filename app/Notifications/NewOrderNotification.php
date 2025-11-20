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
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new NewOrderMail($this->order, $this->user))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Pesanan baru #{$this->order->id} dari {$this->user->name}",
            'order_id' => $this->order->id,
            'user_name' => $this->user->name,
            'total' => $this->order->total,
            'url' => '/admin/pesanan/' . $this->order->id,
            'type' => 'new_order'
        ];
    }
}
