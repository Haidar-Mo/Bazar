<?php

namespace App\Observers;

use App\Models\Advertisement;
use App\Traits\FirebaseNotificationTrait;

class AdvertisementObserver
{
    use FirebaseNotificationTrait;

    public function updated(Advertisement $advertisement)
    {
        if ($advertisement->isDirty('status') && $advertisement->status === 'active') {

            $this->sendNotificationToTopic(
                $advertisement->main_category_id,
                'إعلان جديد في قسم ' . $advertisement->main_category_name,
                $advertisement->title,
                $advertisement->id
            );
        }
    }

}
