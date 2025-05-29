<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Platform\ListController;
use App\Http\Controllers\Api\Platform\ToggleController;
use App\Http\Controllers\Api\Post\DeleteController;
use App\Http\Controllers\Api\Post\SaveController;
use App\Http\Controllers\Api\Post\UpdateController;
use App\Http\Controllers\Api\User\PostController;
use App\Http\Controllers\Api\Post\UploadImageController;
use App\Http\Controllers\Api\Post\ListController as PostListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login',LoginController::class)->name('user.login');
Route::put('user/{user}/platform/{platform}',ToggleController::class)
->name('platforms.toggle')->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/posts', [PostListController::class, 'index']);
Route::post('/posts', SaveController::class)
->name('posts.save')->middleware('auth:sanctum');
Route::get('/user/{user}/posts', PostController::class)
->name('user.posts')->middleware('auth:sanctum');

Route::put('post/{post}', UpdateController::class)
->name('post.update')->middleware('auth:sanctum');

Route::delete('post/{post}',DeleteController::class)
->name('post.delete')->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->prefix('platforms')->group(function () {
    Route::get('/', ListController::class);
    Route::post('/', \App\Http\Controllers\Api\Platform\StoreController::class);
    Route::delete('/{platform}', \App\Http\Controllers\Api\Platform\DeleteController::class);
});


Route::post('/upload-image', UploadImageController::class)->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/activity-logs', [ActivityLogController::class, 'index']);
