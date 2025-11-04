<?php

namespace App\Notifications;

use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification implements ShouldQueue
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
            ->subject(__('notifications.payment_confirmed_subject', ['order_id' => $this->order->id]))
            ->greeting(__('notifications.payment_confirmed_greeting', ['name' => $notifiable->name]))
            ->line(__('notifications.payment_confirmed_line1', ['order_id' => $this->order->id]))
            ->line(__('notifications.payment_confirmed_line2', ['amount' => number_format($this->order->total_price, 2, '.', ',')]))
            ->action(__('notifications.view_order_details'), route('order.show', $this->order->id))
            ->line(__('notifications.payment_confirmed_line3'));
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
            'amount'   => $this->order->total_price,
            'message'  => __('notifications.payment_confirmed_message', ['order_id' => $this->order->id]),
        ];
    }

}
