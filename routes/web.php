<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::redirect('/', '/products');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProductController::class, 'index'])->middleware(['verified'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
    Route::middleware('is_admin')->group(function () {
       Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
       Route::post('products', [ProductController::class, 'store'])->name('products.store');
       Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
       Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
