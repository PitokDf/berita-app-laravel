<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;

// Route Halaman Depan & Berita
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news', [HomeController::class, 'allNews'])->name('news.all');

// Route untuk Browsing Kategori
Route::get('/categories', [HomeController::class, 'browseCategories'])->name('categories.browse');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Route untuk Manajemen Kategori (CRUD)
Route::prefix('categories/manage')->name('categories.manage.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    // Route untuk auto-generate slug
    Route::get('/generate-slug', [CategoryController::class, 'generateSlug'])->name('generate-slug');
});


// Route untuk Manajemen Berita (CRUD)
Route::prefix('news/manage')->name('news.manage.')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/create', [ArticleController::class, 'create'])->name('create');
    Route::post('/', [ArticleController::class, 'store'])->name('store');
    Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
    Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
    Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
    
    // Route untuk auto-generate slug
    Route::get('/generate-slug', [ArticleController::class, 'generateSlug'])->name('generate-slug');
});
    
Route::get('/news/{slug}', [HomeController::class, 'showArticleDetail'])->name('news.detail');
