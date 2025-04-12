<?php

use App\Enums\TokenAbility;
use App\Models\City;
use Illuminate\Support\Facades\Route;

Route::prefix('city')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        //'role:client'
    ])
    ->group(function () {
        Route::get('index', function () {

            $city = City::all();
            return response()->json([
                'success' => true,
                'message' => 'All cities retrieved !!',
                'data' => $city
            ]);
        });
    });