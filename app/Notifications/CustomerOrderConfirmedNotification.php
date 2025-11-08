<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Session;

class CustomerOrderConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $order;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $locale = null)
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Set locale trước khi tạo nội dung email
        app()->setLocale($this->locale);

        return (new MailMessage)
            ->subject(__('notifications.order_confirmed_subject'))
            ->line(__('notifications.order_confirmed_line', ['order_id' => $this->order->id]))
            ->action(__('notifications.view_order'), config('app.url') . '/order/' . $this->order->id);
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
            'message'  => __('notifications.order_confirmed_message', ['order_id' => $this->order->id]),
        ];
    }
}
