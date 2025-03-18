<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\Auth\RegistrationController;
use App\Http\Controllers\Api\Mobile\Auth\AuthenticationController;
use App\Http\Controllers\Api\Mobile\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Mobile\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::prefix('auth/')->group(function () {

    //** Socialite Routes
    /* Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect']);
     Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback']);*/
    Route::post('social/{provider}', [SocialiteController::class, 'loginWithSocial']);


    //** Email Routes
    Route::post('register', [RegistrationController::class, 'create']);
    Route::post('confirm-email', [RegistrationController::class, 'verifyEmail']);
    Route::post('resend-verification-code', [RegistrationController::class, 'resendVerificationCode']);
    Route::post('information-fill', [RegistrationController::class, 'informationRegistration'])->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value
    ]);
    Route::post('forget-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.request');
    Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
    Route::post('login', [AuthenticationController::class, 'create']);



    Route::post('refresh-token', [AuthenticationController::class, 'refreshToken'])->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value
    ]);

    Route::middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
    ])->
        get('/check-token', function (Request $request) {
            return response()->json([
                'success' => true,
                'message' => 'Token is valid',
                'user' => $request->user(),
            ]);
        });
    Route::post('logout', [AuthenticationController::class, 'delete'])->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value
    ]);
});