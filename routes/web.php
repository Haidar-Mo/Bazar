<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('migrate', function () {

    Artisan::call('migrate');
    $title = 'Migration Done !!';
    return view('artisan-response', compact('title'));
});

Route::get('seed', function () {

    Artisan::call('db:seed');
    $title = 'Seed Done !!';
    return view('artisan-response', compact('title'));
});

Route::get('migrate-refresh', function () {

    Artisan::call('migrate:refresh');
    $title = 'Migration-Refresh Done !!';
    return view('artisan-response', compact('title'));
});

Route::get('migrate-rollback/{path}', function ($path) {
    Artisan::call("migrate:rollback --path={$path}");
    $title = 'Migration Rollback Done !!';
    return view('artisan-response', compact('title'));
});

Route::get('storage-link', function () {
    Artisan::call('storage:link');
    $title = 'Storage linked successfully !!';
    return view('artisan-response', compact('title'));

});

//database\migrations\2025_03_28_192313_create_password_resets_table.php