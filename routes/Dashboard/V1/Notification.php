<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\NotificationController;
use Illuminate\Support\Facades\Route;


Route::prefix('notifications')
    ->middleware([])
    ->group(function () {

        Route::post('unicast/{id}', [NotificationController::class, 'unicastNotification']);
    });