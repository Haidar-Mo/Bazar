<?php

use App\Http\Controllers\Api\Mobile\AdvertisementController;
use App\Http\Controllers\Api\Mobile\CitiesController;
use App\Http\Controllers\Api\Mobile\HomePageController;
use App\Http\Controllers\Api\Mobile\CategoriesController;

use Illuminate\Support\Facades\Route;

Route::prefix('guest/')->group(function () {

    Route::get('advertisement/{id}', [AdvertisementController::class, 'show'])->name('guest-ad');
    Route::get('advertisement/similar/{id}', [AdvertisementController::class, 'getSimilarAds']);
    Route::get('homepage', [HomePageController::class, 'index']);
    Route::get('homepage/filter', [HomePageController::class, 'indexWithFilter']);
    Route::get('cities/index', [CitiesController::class, 'index']);
    Route::apiResource('categories', CategoriesController::class);

});