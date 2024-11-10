<?php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('products.index'));
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::post('/products/faults', [ProductController::class, 'addFault'])->name('products.addFault');
Route::post('/products/entries', [ProductController::class, 'addEntry'])->name('products.addEntry');
Route::post('/products/sales', [ProductController::class, 'registerSale'])->name('products.registerSale');
