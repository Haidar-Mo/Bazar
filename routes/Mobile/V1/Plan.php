<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\PlanController;
use Illuminate\Support\Facades\Route;


    Route::middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
      //  'role:client'
    ])
    ->group(function () {

           Route::apiResource('plans',PlanController::class);

    });
