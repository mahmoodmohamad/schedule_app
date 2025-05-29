<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/{any}', function () {
    return view('dashboard');   
})->where('any', '.*');


//Route::get('/login', function () {
  //  return view('dashboard'); 
//})->name('login');
//Route::view('/dashboard', 'dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

