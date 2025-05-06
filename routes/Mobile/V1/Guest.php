<?php

use App\Http\Controllers\Api\Mobile\AdvertisementController;
use App\Http\Controllers\Api\Mobile\HomePageController;

use Illuminate\Support\Facades\Route;

Route::prefix('guest/')->group(function () {

    Route::get('advertisement/{id}', [AdvertisementController::class, 'show']);
    Route::get('homepage', [HomePageController::class, 'index']);

});