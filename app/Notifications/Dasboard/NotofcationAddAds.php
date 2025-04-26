<?php

namespace App\Notifications\Dasboard;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotofcationAddAds extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $body)
    {
    }

    public function databaseType(object $notifiable): string
    {
        return 'Ads-Dasboard-Notification';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'اضافة اعلان',
            'body' => $this->body

        ];
    }
}
