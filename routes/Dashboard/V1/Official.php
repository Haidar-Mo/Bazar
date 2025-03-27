<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\OfficialsController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    //  'auth:sanctum',
    //  'ability:' . TokenAbility::ACCESS_API->value,
    //  'role:admin'
])->apiResource('officials', OfficialsController::class);


Route::prefix('officials')->middleware([
    //  'auth:sanctum',
    //  'ability:' . TokenAbility::ACCESS_API->value,
    //  'role:admin' 
])->group(function () {
    Route::post('remove-permission/{id}', [OfficialsController::class, 'removePermission']);
    Route::post('add-permission/{id}', [OfficialsController::class, 'addPermission']);
});
