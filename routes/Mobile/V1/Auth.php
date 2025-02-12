<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\Auth\RegistrationController;
use App\Http\Controllers\Api\Mobile\Auth\AuthenticationController;
use App\Http\Controllers\Api\Mobile\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {


    Route::post('register', [RegistrationController::class, 'create']);
    Route::post('confirm-email', [RegistrationController::class, 'verifyEmail']);
    Route::post('resend-verification-code', [RegistrationController::class, 'resendVerificationCode']);
    Route::post('information-fill', [RegistrationController::class, 'informationRegistration']);

    Route::post('forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.request');
    Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');

    Route::post('refreshToken', [AuthenticationController::class, 'refreshToken'])->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value
    ]);
    
    Route::post('login', [AuthenticationController::class, 'create']);

    Route::post('logout', [AuthenticationController::class, 'delete'])->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value
    ]);
});