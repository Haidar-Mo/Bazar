<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $token)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject(Lang::get('RESET PASSWORD CODE'))
        ->greeting(Lang::get('Hello!'))
        ->line(Lang::get('Your reset password code is:'))
        ->line(Lang::get("**{$this->token}**"))
        ->line(Lang::get('This code will expire in 10 minutes.'))
        ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
        ->line(Lang::get('If you did not request a password reset, no further action is required.'))
        ->line(Lang::get('Thank you for using our services!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
