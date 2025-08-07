<?php

use App\Http\Controllers\Api\Mobile\ShareLinkController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('/share-link/advertisement/{id}', [ShareLinkController::class, 'handle'])->name('share-link');


//! Test subscribe and send notification to topic

/*Route::get('/test-subscribe', function () {
    $deviceToken = request('token');
    $topic = '13';

    $notifier = new class {
        use \App\Traits\FirebaseNotificationTrait;
    };

    return $notifier->subscribeToTopic($deviceToken, $topic);
});


Route::get('/test-notification', function () {
    $deviceToken = request('token');
    $Request = (object) [
       'title' => 'قبول اعلان',
        'body' => 'تم قبول اعلانك من قبل الادمن',
       'type' => 'approved-Ads',
    ];

    $notifier = new class {
        use \App\Traits\FirebaseNotificationTrait;
    };

    return $notifier->unicast($Request, $deviceToken);
});


Route::get('/test-send-notification', function () {
    $topic = '13';
    $title = ' إشعار تجربة';
    $body = 'هذا إشعار اختباري لنوع مخصص';
    $advertisementId = 117;
    $type = 'add-Ads';

    $notifier = new class {
        use \App\Traits\FirebaseNotificationTrait;
    };

    return $notifier->sendNotificationToTopic($topic, $title, $body, $advertisementId, $type);
});


*/

