<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\RuleController;
use Illuminate\Support\Facades\Route;

Route::prefix('rules')
    ->middleware([
        //'auth:sanctum',
        //'ability:' . TokenAbility::ACCESS_API->value,
       // 'role:client'
    ])
    ->group(function () {
            Route::apiResource('rules',RuleController::class)->only('index');
    });

