<?php

use Illuminate\Support\Facades\Route;

Route::view('/{any?}', 'dashboard')->where('any', '^(?!api).*$');

//Route::get('/login', function () {
  //  return view('dashboard'); 
//})->name('login');
//Route::view('/dashboard', 'dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

