<?php

use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\MaterialsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

// Категории
Route::get('/categories', [MaterialCategoryController::class, 'indexWeb'])->name('categories.index');

// Материалы конкретной категории (обновленный маршрут без /materials)
Route::get('/categories/{slug}', [MaterialCategoryController::class, 'showCategoryMaterials'])->name('categories.materials');

// Старые маршруты для обратной совместимостиц
Route::get('/materials/{id}', fn($id) => view('materials'));

Route::get('/project', function () {
    return view('project');
})->name('project.index');

Route::get('/project/create', function () {
    return view('project.create');
})->name('project.create');

Route::get('/project/{id}', function ($id) {
    return view('project.show', ['projectId' => $id]);
})->name('project.show');

Route::get('/test', fn() => view('test'));
Route::get('/project', function () {
    return view('project');
})->name('project.index');

Route::get('/project/create', function () {
    return view('create_project'); // без .blade.php
})->name('project.create');

// Замените существующий маршрут просмотра сметы
Route::get('/project/{id}', function ($id) {
    return view('show_projects', ['projectId' => $id]);
})->name('project.show');
