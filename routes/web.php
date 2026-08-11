<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
Route::resource('products', ProductController::class);


Route::get('/', function () {
    return redirect()->route('products.index');
});
