<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class ResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The locale for this notification.
     *
     * @var string
     */
    public $locale;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @param  string|null  $locale
     * @return void
     */
    public function __construct($token, $locale = null)
    {
        $this->token = $token;
        // Lấy locale từ parameter hoặc từ session hoặc fallback to app default
        $this->locale = $locale ?: Session::get('locale', config('app.locale'));
        
        Log::info('ResetPassword notification created with locale: ' . $this->locale);
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Set locale trước khi tạo nội dung email
        app()->setLocale($this->locale);
        
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        Log::info('Generated password reset URL: ' . $url);
        Log::info('Sending reset email to: ' . $notifiable->getEmailForPasswordReset());
        Log::info('Current locale for email: ' . $this->locale);

        $expireMinutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject(__('auth.reset_password_subject'))
            ->greeting(__('auth.reset_password_greeting'))
            ->line(__('auth.reset_password_line'))
            ->action(__('auth.reset_password_action'), $url)
            ->line(__('auth.reset_password_expire', ['count' => $expireMinutes]))
            ->line(__('auth.reset_password_no_action'))
            ->salutation(__('auth.reset_password_regards'))
            ->salutation(__('auth.reset_password_signature'))
            ->with([
                'actionText' => __('auth.reset_password_action'),
                'actionUrl' => $url,
                'displayableActionUrl' => $url,
                'troubleText' => __('auth.reset_password_trouble'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
            'locale' => $this->locale,
        ];
    }
}
