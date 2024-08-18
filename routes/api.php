<?php

use App\Http\Controllers\Api\ProductController as ApiProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('api-products', ApiProductController::class)->names([
    'index' => 'api-products.index',
    'show' => 'api-products.show',
    'store' => 'api-products.store',
    'update' => 'api-products.update',
    'destroy' => 'api-products.destroy',
]);