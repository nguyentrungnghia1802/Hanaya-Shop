<?php

namespace App\Notifications;

use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order #' . $this->order->id . ' Completed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your order #' . $this->order->id . ' has been completed successfully.')
            ->line('Thank you for shopping with us!')
            ->action('View Order Details', route('order.show', $this->order->id))
            ->line('We hope you enjoy your purchase.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Order #' . $this->order->id . ' has been completed',
        ];
    }
}
