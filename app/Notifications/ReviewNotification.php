<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $review;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($review, $user)
    {
        $this->review = $review;
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
            ->line('Review baru diterima!')
            ->action('Lihat Review', url('/admin/reviews'))
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
            'message' => "Review baru dari {$this->user->name} untuk produk '{$this->review->product->nama_produk}'",
            'review_id' => $this->review->id,
            'product_name' => $this->review->product->nama_produk,
            'user_name' => $this->user->name,
            'rating' => $this->review->rating,
            'url' => '/admin/reviews',
            'type' => 'review'
        ];
    }
}
