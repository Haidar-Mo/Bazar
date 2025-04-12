<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\HomePageController;
use Illuminate\Support\Facades\Route;

Route::prefix('homePage')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        //'role:client'
    ])
    ->group(function () {
        Route::apiResource('home', HomePageController::class);

        Route::get('index/filter', [HomePageController::class, 'indexWithFilter']);
    });
