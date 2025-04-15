<?php

use Illuminate\Support\Facades\Route;
use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\VerificationRequestController;


Route::prefix('verification-requests')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        //'role:client'
    ])
    ->group(function () {

        Route::get('show', [VerificationRequestController::class, 'show']);
        Route::post('create', [VerificationRequestController::class, 'store']);

    });