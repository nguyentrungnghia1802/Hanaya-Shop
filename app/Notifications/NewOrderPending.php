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
                    ->subject('Yêu cầu xác nhận đơn hàng mới')
                    ->line('Một đơn hàng mới đang chờ xác nhận.')
                    ->line('Mã đơn hàng: #' . $this->order->id)
                    ->action('Xem đơn hàng', url('/admin/orders/' . $this->order->id));
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
            'user_name' => $this->order->user->name ?? 'Khách hàng', // Đảm bảo $this->order->user đã được load (eager loading) nếu bạn dùng dòng này
            'message' => 'Đơn hàng #' . $this->order->id . ' đang chờ xác nhận.',
        ];
    }

    
}