<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\FavoriteListItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('favorites')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:client'
    ])
    ->group(function () {


            Route::apiResource('items',FavoriteListItemController::class);
           // Route::delete('items/delete/{id}/{list_id}',[FavoriteListItemController::class,'destroy']);




    });
