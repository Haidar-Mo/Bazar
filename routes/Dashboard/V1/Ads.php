<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\AdvertisementController;
use Illuminate\Support\Facades\Route;


Route::prefix('advertisements')
        ->middleware([
                //'auth:sanctum',
                //'ability:' . TokenAbility::ACCESS_API->value,
                // 'role:admin'
        ])->group(function () {

                Route::get('index', [AdvertisementController::class, 'index']);
                Route::get('show/{id}', [AdvertisementController::class, 'show']);
                Route::post('update/{id}', [AdvertisementController::class, 'update']);
                Route::delete('delete/{id}', [AdvertisementController::class, 'destroy']);
                Route::post('approve/{id}', [AdvertisementController::class, 'approve']);
                Route::post('reject/{id}', [AdvertisementController::class, 'reject']);

        });



