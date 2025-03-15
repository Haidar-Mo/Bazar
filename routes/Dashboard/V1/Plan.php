<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\PlanController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    //  'auth:sanctum',
    //  'ability:' . TokenAbility::ACCESS_API->value,
    //  'role:admin'
])->apiResource('plans', PlanController::class);

