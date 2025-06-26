<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AddAdsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $categoryId;
    public string $categoryName;
    public string $title;
    public string $advertisementId;

    public function __construct(string $categoryId, string $categoryName, string $title, string $advertisementId)
    {
        $this->categoryId       = $categoryId;
        $this->categoryName     = $categoryName;
        $this->title            = $title;
        $this->advertisementId  = $advertisementId;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

        public function databaseType(object $notifiable): string
    {
        return 'add-Ads';
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'             => 'إعلان جديد في قسم ' . $this->categoryName,
            'body'              => $this->title,
            'category_id'       => $this->categoryId,
            'category_name'     => $this->categoryName,
            'advertisement_id'  => $this->advertisementId,
        ];
    }
}
