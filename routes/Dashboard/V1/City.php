<?php

use App\Http\Controllers\Api\Dashboard\CitesCotroller;
use Illuminate\Support\Facades\Route;


    Route::middleware([
        //  'auth:sanctum',
        //  'ability:' . TokenAbility::ACCESS_API->value,
        //  'role:admin'
    ])->apiResource('cities', CitesCotroller::class);


