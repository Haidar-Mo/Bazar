<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\AdvertisementController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsEndedPlan;
Route::prefix('Advertisements/')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
       // 'role:client'
    ])
    ->group(function () {


        Route::prefix('ads')->group(function () {
            Route::apiResource('advertisements', AdvertisementController::class)->only(['index','update','show','destroy']);
            Route::post('advertisements',[AdvertisementController::class,'store'])->middleware(IsEndedPlan::class);
        });


        Route::get('filter', [AdvertisementController::class, 'indexWithFilter']);
        Route::get('similar/{id}',[AdvertisementController::class, 'getSimilarAds']);

        Route::get('share/{id}', [AdvertisementController::class, 'share']);

    });
