<?php

use App\Http\Controllers\Api\Dashboard\CategoryController;
use Illuminate\Support\Facades\Route;


Route::prefix('categories')
    ->middleware([])
    ->group(function () {

        Route::get('index', [CategoryController::class, 'index']);
    });