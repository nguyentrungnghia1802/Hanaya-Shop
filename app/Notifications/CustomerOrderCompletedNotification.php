<?php

namespace App\Notifications;

use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Session;

class CustomerOrderCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $locale = null)
    {
        $this->order = $order;
        // Lấy locale từ parameter hoặc từ session hoặc fallback to app default
        $this->locale = $locale ?: Session::get('locale', config('app.locale'));
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
        // Set locale trước khi tạo nội dung email
        app()->setLocale($this->locale);

        return (new MailMessage)
            ->subject(__('notifications.order_completed_subject', ['order_id' => $this->order->id]))
            ->greeting(__('notifications.order_completed_greeting', ['name' => $notifiable->name]))
            ->line(__('notifications.order_completed_line1', ['order_id' => $this->order->id]))
            ->line(__('notifications.order_completed_line2'))
            ->action(__('notifications.view_order_details'), config('app.url').'/order/'.$this->order->id)
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
            'message' => __('notifications.order_completed_message', ['order_id' => $this->order->id]),
        ];
    }
}
