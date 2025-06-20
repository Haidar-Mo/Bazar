<?php

use App\Http\Controllers\Api\Mobile\ShareLinkController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('migrate', function () {

    Artisan::call('migrate');
    $title = 'Migration Done !!';
    return view('artisan-response', compact('title'));
});

Route::get('seed', function () {

    Artisan::call('db:seed');
    $title = 'Seed Done !!';
    return view('artisan-response', compact('title'));
});

Route::get('migrate-refresh', function () {

    Artisan::call('migrate:refresh');
    $title = 'Migration-Refresh Done !!';
    return view('artisan-response', compact('title'));
});

Route::get('migrate-rollback/{path}', function ($path) {
    Artisan::call("migrate:rollback --path={$path}");
    $title = 'Migration Rollback Done !!';
    return view('artisan-response', compact('title'));
});

Route::get('storage-link', function () {
    Artisan::call('storage:link');
    $title = 'Storage linked successfully !!';
    return view('artisan-response', compact('title'));

});

Route::get('/share-link/advertisement/{id}', [ShareLinkController::class, 'handle'])->name('share-link');




//! Test subscrip and send notification to topic
Route::get('/test-subscribe', function () {
    $deviceToken = request('token');
    $topic = '2';

    $notifier = new class {
        use \App\Traits\FirebaseNotificationTrait;
    };

    return $notifier->subscribeToTopic($deviceToken, $topic);
});


Route::get('/test-notification', function () {
    $deviceToken = request('token');
    $Request = (object) [
                    'title' => 'رسالة جديدة',
                    'body' => 'لديك رسالة  ' ,
                    'type' => 'chat',
                ];

    $notifier = new class {
        use \App\Traits\FirebaseNotificationTrait;
    };

    return $notifier->unicast($Request,$deviceToken);
});


Route::get('/test-send-notification', function () {
    $topic = '1';
    $title = 'إشعار تجريبي';
    $body = 'هذا إشعار اختبار للتوبيك رقم 1';

    $notifier = new class {
        use \App\Traits\FirebaseNotificationTrait;
    };

    return $notifier->sendNotificationToTopic($topic, $title, $body);
});

