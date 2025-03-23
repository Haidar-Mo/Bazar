<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\RuleController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    //  'auth:sanctum',
    //  'ability:' . TokenAbility::ACCESS_API->value,
    //  'role:admin'
])->apiResource('rules', RuleController::class);
Route::post('update/rule', [RuleController::class, 'update']);

