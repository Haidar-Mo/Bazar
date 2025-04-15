<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptiondNotification extends Notification implements ShouldQueue
{
    use Queueable;


    public function __construct(public $subscriptionName,public $title,public $body)
    {

    }

    public function databaseType(object $notifiable): string
    {
        return 'subscription-plan';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => "{$this->body}:{$this->subscriptionName}",
            
        ];
    }
}
