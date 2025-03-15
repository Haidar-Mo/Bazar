<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\FavoriteController;

use Illuminate\Support\Facades\Route;

Route::prefix('favorites')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:client'
    ])
    ->group(function () {


            Route::apiResource('favorites',FavoriteController::class);




    });
