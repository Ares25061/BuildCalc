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
use App\Http\Controllers\UserController;
use App\Http\Controllers\MaterialConsumptionRatesController;
use App\Http\Controllers\SelectedProjectMaterialsController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Users
Route::apiResource('users', UserController::class);
Route::patch('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);

// MaterialConsumptionRates
Route::apiResource('materialConsumptionRates', MaterialConsumptionRatesController::class);
Route::patch('materialConsumptionRates/{id}', [MaterialConsumptionRatesController::class, 'update']);
Route::delete('materialConsumptionRates/{id}', [MaterialConsumptionRatesController::class, 'destroy']);

// SelectedProjectMaterials
Route::apiResource('selectedProjectMaterials', SelectedProjectMaterialsController::class);
Route::patch('selectedProjectMaterials/{id}', [SelectedProjectMaterialsController::class, 'update']);
Route::delete('selectedProjectMaterials/{id}', [SelectedProjectMaterialsController::class, 'destroy']);

// Materials
Route::apiResource('materials', MaterialsController::class);
Route::patch('materials/{id}', [MaterialsController::class, 'update']);
Route::delete('materials/{id}', [MaterialsController::class, 'destroy']);

// Material categories
Route::apiResource('materialCategories', MaterialCategoryController::class);
Route::patch('materialCategories/{id}', [MaterialCategoryController::class, 'update']);
Route::delete('materialCategories/{id}', [MaterialCategoryController::class, 'destroy']);

// Material prices
Route::apiResource('materialPrices', MaterialPricesController::class);
Route::patch('materialPrices/{id}', [MaterialPricesController::class, 'update']);
Route::delete('materialPrices/{id}', [MaterialPricesController::class, 'destroy']);

// Projects
Route::apiResource('projects', ProjectsController::class);
Route::patch('projects/{id}', [ProjectsController::class, 'update']);
Route::delete('projects/{id}', [ProjectsController::class, 'destroy']);

// Project items
Route::apiResource('projectItems', ProjectItemsController::class);
Route::patch('projectItems/{id}', [ProjectItemsController::class, 'update']);
Route::delete('projectItems/{id}', [ProjectItemsController::class, 'destroy']);

// Suppliers
Route::apiResource('suppliers', SuppliersController::class);
Route::patch('suppliers/{id}', [SuppliersController::class, 'update']);
Route::delete('suppliers/{id}', [SuppliersController::class, 'destroy']);

// Work types
Route::apiResource('workTypes', WorkTypesController::class);
Route::patch('workTypes/{id}', [WorkTypesController::class, 'update']);
Route::delete('workTypes/{id}', [WorkTypesController::class, 'destroy']);
