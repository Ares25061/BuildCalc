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

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public material routes (for catalog)
Route::apiResource('materials', MaterialsController::class)->only(['index', 'show']);
Route::apiResource('materialCategories', MaterialCategoryController::class)->only(['index', 'show']);

// Protected routes (require authentication)
Route::middleware('auth:api')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

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

    // Materials (protected routes - create, update, delete)
    Route::apiResource('materials', MaterialsController::class)->except(['index', 'show']);
    Route::patch('materials/{id}', [MaterialsController::class, 'update']);

    // Material categories (protected routes - create, update, delete)
    Route::apiResource('materialCategories', MaterialCategoryController::class)->except(['index', 'show']);
    Route::patch('materialCategories/{id}', [MaterialCategoryController::class, 'update']);
    Route::delete('materialCategories/{id}', [MaterialCategoryController::class, 'destroy']);

    // Material prices
    Route::apiResource('materialPrices', MaterialPricesController::class);
    Route::patch('materialPrices/{id}', [MaterialPricesController::class, 'update']);
    Route::delete('materialPrices/{id}', [MaterialPricesController::class, 'destroy']);

    // Projects - все маршруты защищены
    Route::apiResource('projects', ProjectsController::class);
    Route::patch('projects/{id}', [ProjectsController::class, 'update']);
    Route::delete('projects/{id}', [ProjectsController::class, 'destroy']);

    // Project materials management
    Route::get('/projects/{projectId}/materials', [ProjectsController::class, 'getMaterials']);
    Route::post('/projects/{projectId}/materials', [ProjectsController::class, 'addMaterial']);
    Route::put('/projects/{projectId}/materials/{materialId}', [ProjectsController::class, 'updateMaterial']);
    Route::delete('/projects/{projectId}/materials/{materialId}', [ProjectsController::class, 'removeMaterial']);

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
});

// Fallback for undefined API routes
Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found. Please check the documentation.',
        'status' => 404
    ], 404);
});
