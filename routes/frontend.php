<?php

use Illuminate\Support\Facades\Route;
use Secretwebmaster\WncmsFaqs\Http\Controllers\Frontend\FaqController;
use Secretwebmaster\WncmsFaqs\Models\Faq;

Route::name('frontend.')->middleware(['is_installed', 'has_website', 'full_page_cache'])->group(function () {
    Route::get('faq/{slug}', [FaqController::class, 'single'])->name('faqs.single');
    Route::get('faq/search/{keyword}', [FaqController::class, 'search_result'])->name('faqs.search_result');
    Route::post('faq/search', [FaqController::class, 'search'])->name('faqs.search');
    Route::get('faq/{type}/{slug}', [FaqController::class, 'tag'])->where('type', wncms()->tag()->getTagTypesForRoute(Faq::class))->name('faqs.tag');
});