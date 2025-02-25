<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\CVController;
use Illuminate\Support\Facades\Route;

Route::prefix('profiles')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:client'
    ])
    ->group(function () {

        Route::prefix('cv')->group(function () {
            Route::get('show', [CVController::class, 'show']);
            Route::post('create', [CVController::class, 'store']);
            Route::put('update', [CVController::class, 'update']);
        });



    });
