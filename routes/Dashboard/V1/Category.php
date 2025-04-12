<?php

use App\Http\Controllers\Api\Dashboard\CategoriesController;
use Illuminate\Support\Facades\Route;


    Route::prefix('categories')->
    middleware([
        //  'auth:sanctum',
        //  'ability:' . TokenAbility::ACCESS_API->value,
        //  'role:admin'
    ])->group(function (){

        Route::get('index',[CategoriesController::class,'index']);
    }); 


