<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
     
Route::get('products/create', [ProductController::class, 'create']);

Route::get('products/search', [ProductController::class, 'search']);



Route::get('/', function () {
    return view('welcome');
});
