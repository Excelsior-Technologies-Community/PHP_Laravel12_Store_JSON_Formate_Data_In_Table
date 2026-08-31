<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [ProductController::class, 'dashboard']
)->name('dashboard');

Route::get(
    'dashboard',
    [ProductController::class, 'dashboard']
)->name('products.dashboard');


/*
|--------------------------------------------------------------------------
| Bulk Actions
|--------------------------------------------------------------------------
*/

Route::post(
    'products/bulk-action',
    [ProductController::class, 'bulkAction']
)->name('products.bulk-action');


/*
|--------------------------------------------------------------------------
| Trash
|--------------------------------------------------------------------------
*/

Route::get(
    'products/trash',
    [ProductController::class, 'trash']
)->name('products.trash');

Route::put(
    'products/{id}/restore',
    [ProductController::class, 'restore']
)->name('products.restore');

Route::delete(
    'products/{id}/force-delete',
    [ProductController::class, 'forceDelete']
)->name('products.force-delete');


/*
|--------------------------------------------------------------------------
| Duplicate
|--------------------------------------------------------------------------
*/

Route::post(
    'products/{product}/duplicate',
    [ProductController::class, 'duplicate']
)->name('products.duplicate');


/*
|--------------------------------------------------------------------------
| CSV Export
|--------------------------------------------------------------------------
*/

Route::get(
    'products/export-csv',
    [ProductController::class, 'exportCsv']
)->name('products.export-csv');


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
| Product CRUD
|--------------------------------------------------------------------------
*/

<<<<<<< HEAD
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
=======
Route::resource(
    'products',
    ProductController::class
);
>>>>>>> development
