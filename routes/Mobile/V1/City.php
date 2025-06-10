<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\CitiesController;
use Illuminate\Support\Facades\Route;

Route::prefix('city/')
    ->middleware([
        // 'auth:sanctum',
        // 'ability:' . TokenAbility::ACCESS_API->value,
        //'role:client'
    ])
    ->group(function () {
        Route::get('index', [CitiesController::class, 'index']);

    });