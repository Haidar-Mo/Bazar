<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\ReportController;
use App\Http\Controllers\Api\Mobile\CVController;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Route;

Route::prefix('reports')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
       // 'role:client'
    ])
    ->group(function () {


            Route::apiResource('reports',ReportController::class);




    });
