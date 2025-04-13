<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\NotificationController;
use Illuminate\Support\Facades\Route;


Route::prefix('notifications')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        Route::get('index',[NotificationController::class,'index']);
        Route::post('read/{id}',[NotificationController::class,'markAsRead']);
        Route::post('read-all',[NotificationController::class,'markAllAsRead']);
        Route::post('unicast/{id}', [NotificationController::class, 'unicastNotification']);

    });
