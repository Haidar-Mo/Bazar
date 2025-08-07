<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\MessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('messages')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
       // 'role:client'
    ])
    ->group(function () {
            Route::apiResource('messages',MessageController::class);
    });
