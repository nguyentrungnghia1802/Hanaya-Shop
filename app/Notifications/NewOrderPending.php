<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderPending extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order) // <<< SỬA TẠI ĐÂY: Thêm $order vào tham số
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject(__('notifications.new_order_request_subject')) 
                    ->line(__('notifications.new_order_request_line')) 
                    ->line(__('notifications.order_code') . ' #' . $this->order->id) 
                    ->action(__('notifications.view_order'), url('/admin/orders/' . $this->order->id)); 
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id'  => $this->order->id,
            'user_name' => $this->order->user->name ?? __('notifications.guest'), 
            'message'   => __('notifications.order_waiting_confirmation', ['order_id' => $this->order->id]),
        ];
    }


    
}