<?php

namespace App\Observers;

use App\Models\Advertisement;
use App\Traits\FirebaseNotificationTrait;

class AdvertisementObserver
{
    use FirebaseNotificationTrait;

  public function created(Advertisement $advertisement)
{
    /*$this->sendNotificationToTopic(
        $advertisement->main_category_id,
        'إعلان جديد في قسم ' . $advertisement->main_category_name,
        $advertisement->title,
        $advertisement->id
    );*/
}



}
