<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\RateController;
use Illuminate\Support\Facades\Route;

Route::prefix('rates')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
       // 'role:client'
    ])
    ->group(function () {
            Route::apiResource('rates',RateController::class);
            Route::post('report/{id}',action: [RateController::class ,'report']);
    });
