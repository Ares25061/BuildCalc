<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialCategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Categories
Route::apiResource('material_categories', MaterialCategoryController::class);
Route::patch('material_categories/{id}', [MaterialCategoryController::class, 'update']);
Route::delete('material_categories/{id}', [MaterialCategoryController::class, 'destroy']);

