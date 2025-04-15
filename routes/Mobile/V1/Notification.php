<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\NotificationController;
use Illuminate\Support\Facades\Route;


    Route::middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
      //  'role:client'
    ])
    ->group(function () {

           Route::apiResource('notifications',NotificationController::class);

    });
