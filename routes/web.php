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
