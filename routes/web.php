<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| JSON Import / Export
|--------------------------------------------------------------------------
*/

Route::get(
    'products/import',
    [ProductController::class, 'importForm']
)->name('products.import.form');

Route::post(
    'products/import',
    [ProductController::class, 'import']
)->name('products.import');

Route::get(
    'products/export',
    [ProductController::class, 'export']
)->name('products.export');


/*
|--------------------------------------------------------------------------
| JSON Editor
|--------------------------------------------------------------------------
*/

Route::get(
    'products/{product}/edit-json',
    [ProductController::class, 'editJson']
)->name('products.edit-json');

Route::put(
    'products/{product}/edit-json',
    [ProductController::class, 'updateJson']
)->name('products.update-json');


/*
|--------------------------------------------------------------------------
| Product Search
|--------------------------------------------------------------------------
*/

Route::get(
    'products/search',
    [ProductController::class, 'search']
)->name('products.search');


/*
|--------------------------------------------------------------------------
| Product CRUD
|--------------------------------------------------------------------------
*/

Route::resource('products', ProductController::class);


<<<<<<< HEAD
=======
/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

>>>>>>> development
Route::get('/', function () {
    return redirect()->route('products.index');
});