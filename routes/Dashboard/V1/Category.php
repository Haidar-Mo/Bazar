<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\CategoriesController;
use Illuminate\Support\Facades\Route;


Route::middleware([
/*     'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'role:admin' */
])->apiResource('categories', CategoriesController::class);

Route::middleware([
/*     'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'role:admin' */
])->post('categories/change-appointmentable', [CategoriesController::class, 'changeAppointmentable']);


