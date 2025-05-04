<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Mobile\CVController;
use App\Http\Controllers\Api\Mobile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('profiles')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        // 'role:client'
    ])
    ->group(function () {

        Route::get('show/{id}', [ProfileController::class, 'show']);
        Route::post('update', [ProfileController::class, 'update']);
        Route::post('update/image', [ProfileController::class, 'updateImage']);
        Route::post('delete/image', [ProfileController::class, 'deleteImage']);

        Route::get('ads/{id}', [ProfileController::class, 'showAds']);
        Route::get('rates/{id}', [ProfileController::class, 'showRates']);

        Route::prefix('cv')->group(function () {
            Route::get('show', [CVController::class, 'show']);
            Route::post('create', [CVController::class, 'store']);
            Route::post('update', [CVController::class, 'update']);

            Route::post('file', [CVController::class, 'addCvFile']);
            Route::delete('file', [CVController::class, 'deleteCvFile']);

            Route::post('language', [CVController::class, 'addLanguage']);
            Route::delete('language/{id}', [CVController::class, 'deleteLanguage']);

            Route::post('experience', [CVController::class, 'addExperience']);
            Route::delete('experience/{id}', [CVController::class, 'deleteExperience']);

            Route::post('qualification', [CVController::class, 'addQualification']);
            Route::delete('qualification/{id}', [CVController::class, 'deleteQualification']);

            Route::post('skill', [CVController::class, 'addSkill']);
            Route::delete('skill/{id}', [CVController::class, 'deleteSkill']);

            Route::post('link', [CVController::class, 'addLink']);
            Route::delete('link/{id}', [CVController::class, 'deleteLink']);

            Route::post('document', [CVController::class, 'addDocument']);
            Route::delete('document/{id}', [CVController::class, 'deleteDocument']);

        });

    });

Route::get('profiles/cv/download/{name}', [CVController::class, 'downloadCvResume']);
Route::get('profiles/document/download/{name}', [CVController::class, 'downloadCvDocument']);