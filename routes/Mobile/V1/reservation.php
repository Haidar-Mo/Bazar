<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\ReservationController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,

])->prefix('reservations')->group(function () {
    Route::get('/index/received', [ReservationController::class, 'indexReceived']);
    Route::get('/index/sent', [ReservationController::class, 'indexSended']);
    Route::get('/show/{id}', [ReservationController::class, 'show']);
    Route::post('/create', [ReservationController::class, 'store']);
    Route::post('/accept/{id}', [ReservationController::class, 'accept']);
    Route::post('/reject/{id}', [ReservationController::class, 'reject']);
});