<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        //'role:client'
    ])
    ->group(function () {
            Route::apiResource('categories',CategoriesController::class);
    });
