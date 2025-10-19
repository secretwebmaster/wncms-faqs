<?php

use Illuminate\Support\Facades\Route;
use Secretwebmaster\WncmsFaqs\Http\Controllers\Api\V1\FaqController;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::prefix('faqs')->name('faqs.')->controller(FaqController::class)->group(function () {
        Route::post('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('{id}', 'show')->name('show');
    });
});

// Custom user-defined FAQ API routes
if (file_exists(base_path('routes/custom_faq_api.php'))) {
    include base_path('routes/custom_faq_api.php');
}
