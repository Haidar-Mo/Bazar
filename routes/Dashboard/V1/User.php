<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\UserController;
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
    });