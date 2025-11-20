<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WishlistNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $product;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($product, $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Produk ditambahkan ke wishlist!')
            ->action('Lihat Produk', url('/produk/' . $this->product->slug))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Produk '{$this->product->nama_produk}' ditambahkan ke wishlist oleh {$this->user->name}",
            'product_id' => $this->product->id_produk,
            'product_name' => $this->product->nama_produk,
            'user_name' => $this->user->name,
            'url' => '/produk/' . $this->product->slug,
            'type' => 'wishlist'
        ];
    }
}
