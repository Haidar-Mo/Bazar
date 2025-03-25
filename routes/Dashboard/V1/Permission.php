<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    //  'auth:sanctum',
    //  'ability:' . TokenAbility::ACCESS_API->value,
    //  'role:admin'
])->apiResource('permissions', PermissionController::class);

