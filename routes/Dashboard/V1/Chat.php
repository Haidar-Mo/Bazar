<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Dashboard\ChatController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    //  'auth:sanctum',
    //  'ability:' . TokenAbility::ACCESS_API->value,
    //  'role:admin'
])->apiResource('chats', ChatController::class);

