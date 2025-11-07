<?php

use App\Http\Controllers\AuthController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('materials', MaterialsController::class)->only(['index', 'show']);
Route::apiResource('materialCategories', MaterialCategoryController::class)->only(['index', 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::apiResource('users', UserController::class);
    Route::patch('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::apiResource('materialConsumptionRates', MaterialConsumptionRatesController::class);
    Route::patch('materialConsumptionRates/{id}', [MaterialConsumptionRatesController::class, 'update']);
    Route::delete('materialConsumptionRates/{id}', [MaterialConsumptionRatesController::class, 'destroy']);

    Route::apiResource('selectedProjectMaterials', SelectedProjectMaterialsController::class);
    Route::patch('selectedProjectMaterials/{id}', [SelectedProjectMaterialsController::class, 'update']);
    Route::delete('selectedProjectMaterials/{id}', [SelectedProjectMaterialsController::class, 'destroy']);

    Route::apiResource('materials', MaterialsController::class)->except(['index', 'show']);
    Route::patch('materials/{id}', [MaterialsController::class, 'update']);

    Route::apiResource('materialCategories', MaterialCategoryController::class)->except(['index', 'show']);
    Route::patch('materialCategories/{id}', [MaterialCategoryController::class, 'update']);
    Route::delete('materialCategories/{id}', [MaterialCategoryController::class, 'destroy']);

    Route::apiResource('materialPrices', MaterialPricesController::class);
    Route::patch('materialPrices/{id}', [MaterialPricesController::class, 'update']);
    Route::delete('materialPrices/{id}', [MaterialPricesController::class, 'destroy']);

    Route::apiResource('projects', ProjectsController::class);
    Route::patch('projects/{id}', [ProjectsController::class, 'update']);
    Route::delete('projects/{id}', [ProjectsController::class, 'destroy']);

    Route::get('/projects/{projectId}/materials', [ProjectsController::class, 'getMaterials']);
    Route::post('/projects/{projectId}/materials', [ProjectsController::class, 'addMaterial']);
    Route::put('/projects/{projectId}/materials/{materialId}', [ProjectsController::class, 'updateMaterial']);
    Route::delete('/projects/{projectId}/materials/{materialId}', [ProjectsController::class, 'removeMaterial']);

    Route::get('/projects/{projectId}/items', [ProjectsController::class, 'getProjectItems'])->middleware('auth:api');
    Route::post('/projects/{projectId}/items', [ProjectsController::class, 'addWorkPosition'])->middleware('auth:api');
    Route::put('/projects/{projectId}/items/{itemId}', [ProjectsController::class, 'updateWorkPosition'])->middleware('auth:api');
    Route::delete('/projects/{projectId}/items/{itemId}', [ProjectsController::class, 'deleteWorkPosition'])->middleware('auth:api');
    Route::post('/projects/{projectId}/items/reorder', [ProjectsController::class, 'reorderWorkPositions'])->middleware('auth:api');

    Route::apiResource('projectItems', ProjectItemsController::class);
    Route::patch('projectItems/{id}', [ProjectItemsController::class, 'update']);
    Route::delete('projectItems/{id}', [ProjectItemsController::class, 'destroy']);

    Route::apiResource('suppliers', SuppliersController::class);
    Route::patch('suppliers/{id}', [SuppliersController::class, 'update']);
    Route::delete('suppliers/{id}', [SuppliersController::class, 'destroy']);

    Route::apiResource('workTypes', WorkTypesController::class);
    Route::patch('workTypes/{id}', [WorkTypesController::class, 'update']);
    Route::delete('workTypes/{id}', [WorkTypesController::class, 'destroy']);
});
