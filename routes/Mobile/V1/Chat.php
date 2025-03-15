<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\ChatController;
use Illuminate\Support\Facades\Route;

Route::prefix('chats')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:client'
    ])
    ->group(function () {
            Route::apiResource('chats',ChatController::class);
    });
