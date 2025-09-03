<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;
    // Admin notifications sent immediately for reliability
    public $order;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $locale = 'en')
    {
        $this->order = $order;
        // Admin notifications always use English
        $this->locale = 'en';
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
        // Set locale trước khi tạo nội dung email
        // Admin notifications always use English
        app()->setLocale('en');

        return (new MailMessage)
            ->subject(__('notifications.order_cancelled_subject')) // Đơn hàng đã bị hủy
            ->line(__('notifications.order_cancelled_line', ['order_id' => $this->order->id])) // Đơn hàng #... đã bị hủy
            ->action(__('notifications.view_order'), config('app.url') . '/admin/order/' . $this->order->id); // Xem đơn hàng
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Admin notifications always use English
        app()->setLocale('en');

        return [
            'order_id' => $this->order->id,
            'message'  => __('notifications.order_cancelled_message', ['order_id' => $this->order->id]),
        ];
    }
}
