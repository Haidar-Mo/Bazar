<?php

namespace App\Notifications\Dasboard;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationAuthenticationRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $body)
    {
    }

    public function databaseType(object $notifiable): string
    {
        return 'Auth-Dashboard-Notification';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'طلب اشتراك بباقة',
            'body' => $this->body

        ];
    }
}
