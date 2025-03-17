<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('Subscriptions')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
      //  'role:client'
    ])
    ->group(function () {

           Route::apiResource('Subscriptions',SubscriptionController::class);

    });
