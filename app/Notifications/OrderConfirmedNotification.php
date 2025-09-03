<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmedNotification extends Notification implements ShouldQueue
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
        //
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
        // Admin notifications always use English
        app()->setLocale('en');

        return (new MailMessage)
            ->subject(__('notifications.order_confirmed_subject'))
            ->line(__('notifications.order_confirmed_line', ['order_id' => $this->order->id]))
            ->action(__('notifications.view_order'), config('app.url') . '/admin/order/' . $this->order->id);
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
            'message'  => __('notifications.order_confirmed_message', ['order_id' => $this->order->id]),
        ];
    }
}
