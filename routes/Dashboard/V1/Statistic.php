<?php


use App\Http\Controllers\Api\Dashboard\StatisticController;
use Illuminate\Support\Facades\Route;


Route::prefix('statistics')
    ->middleware([])
    ->group(function () {

        Route::get('get', [StatisticController::class, 'statistics']);

    });