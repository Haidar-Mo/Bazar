<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\AdvertisementAppointmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsEndedPlan;


Route::prefix('appointments/')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        // 'role:client'
    ])
    ->group(function () {

        Route::get('index', [AdvertisementAppointmentController::class, 'index']);
        Route::post('create/{id}', [AdvertisementAppointmentController::class, 'store']);
        Route::post('accept/{id}', [AdvertisementAppointmentController::class, 'accept']);
        Route::post('reject/{id}', [AdvertisementAppointmentController::class, 'reject']);
        Route::delete('delete/{id}', [AdvertisementAppointmentController::class, 'destroy']);
    });
