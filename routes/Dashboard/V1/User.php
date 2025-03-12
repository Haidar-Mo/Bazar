<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\UserController;
use App\Http\Controllers\Api\Dashboard\UserVerificationRequestController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')->
    middleware([
        //'auth:sanctum',
        //'ability:' . TokenAbility::ACCESS_API->value,
        //'role:admin'
    ])->
    group(function () {

        Route::get('index', [UserController::class, 'index']);
        Route::get('show/{id}', [UserController::class, 'show']);

        Route::post('block/{id}', [UserController::class, 'block']);
        Route::post('unblock/{id}', [UserController::class, 'unblock']);

        Route::prefix('verification-requests')->group(function () {

            Route::get('index', [UserVerificationRequestController::class, 'index']);
            Route::get('show/{id}', [UserVerificationRequestController::class, 'show']);
            Route::post('approve/{id}', [UserVerificationRequestController::class, 'approve']);
            Route::post('reject/{id}', [UserVerificationRequestController::class, 'reject']);
        });
    });