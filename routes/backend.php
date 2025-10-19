<?php

use Illuminate\Support\Facades\Route;
use Secretwebmaster\WncmsFaqs\Http\Controllers\Backend\FaqController;

Route::prefix('panel')->middleware(['auth', 'is_installed', 'has_website'])->group(function () {
    Route::prefix('faqs')->controller(FaqController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:faq_index')->name('faqs.index');
        Route::get('/create', 'create')->middleware('can:faq_create')->name('faqs.create');
        Route::get('/create/{id}', 'create')->middleware('can:faq_clone')->name('faqs.clone');
        Route::get('/{id}/edit', 'edit')->middleware('can:faq_edit')->name('faqs.edit');
        Route::post('/store', 'store')->middleware('can:faq_create')->name('faqs.store');
        Route::patch('/{id}', 'update')->middleware('can:faq_edit')->name('faqs.update');
        Route::delete('/{id}', 'destroy')->middleware('can:faq_delete')->name('faqs.destroy');
        Route::post('/bulk_delete', 'bulk_delete')->middleware('can:faq_bulk_delete')->name('faqs.bulk_delete');
    });
});
