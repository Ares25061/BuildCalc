<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CalculationFormulasController;
use App\Http\Controllers\CalculatorParametersController;
use App\Http\Controllers\ParameterOptionsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Categories
Route::apiResource('categories', CategoriesController::class);
Route::patch('categories/{id}', [CategoriesController::class, 'update']);
Route::delete('categories/{id}', [CategoriesController::class, 'destroy']);

// Calculation Formulas
Route::apiResource('calculation-formulas', CalculationFormulasController::class);
Route::patch('calculation-formulas/{id}', [CalculationFormulasController::class, 'update']);
Route::delete('calculation-formulas/{id}', [CalculationFormulasController::class, 'destroy']);

// Calculator Parameters
Route::apiResource('calculator-parameters', CalculatorParametersController::class);
Route::patch('calculator-parameters/{id}', [CalculatorParametersController::class, 'update']);
Route::delete('calculator-parameters/{id}', [CalculatorParametersController::class, 'destroy']);

// Parameter Options
Route::apiResource('parameter-options', ParameterOptionsController::class);
Route::patch('parameter-options/{id}', [ParameterOptionsController::class, 'update']);
Route::delete('parameter-options/{id}', [ParameterOptionsController::class, 'destroy']);
