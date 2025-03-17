<?php

use App\Enums\TokenAbility;
use App\Models\City;
use Illuminate\Support\Facades\Route;

Route::prefix('city')
    ->middleware([])
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