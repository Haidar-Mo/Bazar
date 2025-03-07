<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\AdvertisementController;
use App\Http\Controllers\Api\Mobile\CVController;
use App\Http\Controllers\Api\Mobile\FavoriteListItemController;
use App\Models\Advertisement;
use App\Models\FavoriteListItem;
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
