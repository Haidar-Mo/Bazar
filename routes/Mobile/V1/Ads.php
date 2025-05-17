<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\AdvertisementController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsEndedPlan;

Route::get('Advertisements/similar/{id}', [AdvertisementController::class, 'getSimilarAds']);

Route::prefix('Advertisements/')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        // 'role:client'
    ])
    ->group(function () {


        Route::prefix('ads')->group(function () {
            Route::apiResource('advertisements', AdvertisementController::class)->only(['index', 'show', 'update', 'destroy']);
            Route::post('advertisements', [AdvertisementController::class, 'store'])->middleware(IsEndedPlan::class);
        });

        Route::get('filter', [AdvertisementController::class, 'indexWithFilter']);

        Route::get('share/{id}', [AdvertisementController::class, 'share']);

        Route::get('job-requests/index', [AdvertisementController::class, 'indexJobRequest']);
        Route::get('job-requests/show/{id}', [AdvertisementController::class, 'showJobRequest']);
        Route::post('job-requests/create/{id}', [AdvertisementController::class, 'createJobRequest']);
    });
