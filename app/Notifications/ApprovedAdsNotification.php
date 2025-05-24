<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovedAdsNotification extends Notification implements ShouldQueue
{
    use Queueable;



    public function __construct(public $ad) {}


    public function databaseType(object $notifiable): string
    {
        return 'approved-Ads';
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'قبول اعلان',
            'body' => 'تم قبول اعلانك "' .$this->ad->title . '" من قبل الادمن',

        ];
    }
}
