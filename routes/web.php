<?php

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


Route::get('/categories', fn() => view('categories'));

Route::get('/materials/{id}', fn($id) => view('materials'));

Route::get('/categories/{slug}/materials', function ($slug) {
    return view('materials');
})->name('categories.materials');

Route::get('/project', function () {
    return view('project'); // страница со списком смет
})->name('project.index');

Route::get('/project/create', function () {
    // Здесь будет форма создания сметы
    return view('project.create');
})->name('project.create');

Route::get('/project/{id}', function ($id) {
    // Передаем ID в представление, если нужно
    return view('project.show', ['projectId' => $id]);
})->name('project.show');


Route::get('/test', fn() => view('test'));
