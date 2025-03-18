<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\AdvertisementController;
use Illuminate\Support\Facades\Route;


Route::middleware([
        //'auth:sanctum',
        //'ability:' . TokenAbility::ACCESS_API->value,
       // 'role:client'
    ]) ->apiResource('advertisements', AdvertisementController::class);



