<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\HomePageController;
use App\Http\Controllers\Api\Mobile\CVController;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Route;

Route::prefix('homePage')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:client'
    ])
    ->group(function () {
    Route::apiResource('home',HomePageController::class);
    });
