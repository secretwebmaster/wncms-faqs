<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['web', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    // Frontend FAQ routes
    if (file_exists(__DIR__ . '/frontend.php')) {
        require __DIR__ . '/frontend.php';
    }

    // Backend FAQ routes
    if (file_exists(__DIR__ . '/backend.php')) {
        require __DIR__ . '/backend.php';
    }
});
