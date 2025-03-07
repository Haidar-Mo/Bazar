<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\AdvertisementController;
use App\Http\Controllers\Api\Mobile\CVController;
use App\Http\Controllers\Api\Mobile\FavoriteController;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Route;

Route::prefix('favorites')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:client'
    ])
    ->group(function () {


            Route::apiResource('favorites',FavoriteController::class);




    });
