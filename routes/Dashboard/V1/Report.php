<?php

use App\Http\Controllers\Api\Dashboard\ReportController;
use Illuminate\Support\Facades\Route;


Route::prefix('reports/')
    ->middleware([])
    ->group(function () {

        Route::get('index', [ReportController::class, 'index']);
        Route::get('show/{id}', [ReportController::class, 'show']);
        Route::delete('delete/{id}', [ReportController::class, 'destroy']);
    });