<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\AdvertisementController;
use App\Http\Controllers\Api\Mobile\CVController;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Route;

Route::prefix('Advertisements')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:client'
    ])
    ->group(function () {

        Route::prefix('ads')->group(function () {
            Route::apiResource('advertisements',AdvertisementController::class);
        });



    });
