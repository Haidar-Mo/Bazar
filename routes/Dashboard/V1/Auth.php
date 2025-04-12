<?php

use App\Enums\TokenAbility;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Dashboard\Auth\{
    LoginController,

};
Route::prefix('dashboard/auth/')->group(function () {

     Route::post('login', [LoginController::class, 'create']);


    Route::get('logout', [LoginController::class, 'destroy'])->middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value
]);
});
