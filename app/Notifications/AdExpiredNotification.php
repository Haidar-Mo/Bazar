<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdExpiredNotification extends Notification implements ShouldQueue
   {
       use Queueable;



       public function __construct(public $adTitle)
       {

       }

       public function via($notifiable)
       {
           return ['database'];
       }

       public function toDatabase($notifiable)
       {
           return [
               'title' => 'إعلان منتهي الصلاحية',
               'body' => 'تم تعطيل إعلانك "'.$this->adTitle.'" بسبب انتهاء فترة النشر.',
              
           ];
       }
   }
