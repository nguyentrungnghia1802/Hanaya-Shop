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
            ->subject(__('notifications.order_completed_subject', ['order_id' => $this->order->id]))
            ->greeting(__('notifications.order_completed_greeting', ['name' => $notifiable->name]))
            ->line(__('notifications.order_completed_line1', ['order_id' => $this->order->id]))
            ->line(__('notifications.order_completed_line2'))
            ->action(__('notifications.view_order_details'), route('order.show', $this->order->id))
            ->line(__('notifications.order_completed_line3'));
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
            'message'  => __('notifications.order_completed_message', ['order_id' => $this->order->id]),
        ];
    }

}
