<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\PlanController;
use App\Http\Controllers\Api\Mobile\CVController;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Route;

Route::prefix('plans')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:client'
    ])
    ->group(function () {

           Route::apiResource('plans',PlanController::class);

    });
