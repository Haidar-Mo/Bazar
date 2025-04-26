<?php

namespace App\Observers;

use App\Models\Advertisement;
use App\Traits\FirebaseNotificationTrait;

class AdvertisementObserver
{
    use FirebaseNotificationTrait;

    public function created(Advertisement $advertisement)
    {

        
        $this->sendNotificationToTopic(
            topic: $advertisement->main_category_name,
            title: 'إعلان جديد في قسم ' . $advertisement->main_category_name,
            body: $advertisement->title
        );
    }
}
