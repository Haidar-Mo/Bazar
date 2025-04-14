<?php

use App\Http\Controllers\Api\Dashboard\CategoriesController;
use Illuminate\Support\Facades\Route;



Route::middleware([
    //  'auth:sanctum',
    //  'ability:' . TokenAbility::ACCESS_API->value,
    //  'role:admin'
])->apiResource('categories', CategoriesController::class);


