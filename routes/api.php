<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\MaterialPricesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectItemsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\WorkTypesController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Users
// ?

// Materials
Route::apiResource('materials', MaterialsController::class);
Route::patch('materials/{id}', [MaterialsController::class, 'update']);
Route::delete('materials/{id}', [MaterialsController::class, 'destroy']);

// Material categories
Route::apiResource('material_categories', MaterialCategoryController::class);
Route::patch('material_categories/{id}', [MaterialCategoryController::class, 'update']);
Route::delete('material_categories/{id}', [MaterialCategoryController::class, 'destroy']);

// Material prices
Route::apiResource('material_prices', MaterialPricesController::class);
Route::patch('material_prices/{id}', [MaterialPricesController::class, 'update']);
Route::delete('material_categories/{id}', [MaterialPricesController::class, 'destroy']);

// Projects
Route::apiResource('projects', ProjectsController::class);
Route::patch('projects/{id}', [ProjectsController::class, 'update']);
Route::delete('projects/{id}', [ProjectsController::class, 'destroy']);

// Project items
Route::apiResource('project_items', ProjectItemsController::class);
Route::patch('project_items/{id}', [ProjectItemsController::class, 'update']);
Route::delete('project_items/{id}', [ProjectItemsController::class, 'destroy']);

// Suppliers
Route::apiResource('suppliers', SuppliersController::class);
Route::patch('suppliers/{id}', [SuppliersController::class, 'update']);
Route::delete('suppliers/{id}', [SuppliersController::class, 'destroy']);

// Work types
Route::apiResource('work_types', WorkTypesController::class);
Route::patch('work_types/{id}', [WorkTypesController::class, 'update']);
Route::delete('work_types/{id}', [WorkTypesController::class, 'destroy']);
