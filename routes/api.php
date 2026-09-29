<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Platform\ListController;
use App\Http\Controllers\Api\Platform\ToggleController;
use App\Http\Controllers\Api\Post\DeleteController;
use App\Http\Controllers\Api\Post\SaveController;
use App\Http\Controllers\Api\Post\UpdateController;
use App\Http\Controllers\Api\Post\UploadImageController;
use App\Http\Controllers\Api\Post\ListController as PostListController;
use App\Http\Controllers\Api\User\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->name('user.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());

    // Posts
    Route::get('/posts', [PostListController::class, 'index']);
    Route::post('/posts', SaveController::class)->name('posts.save');
    Route::get('/user/{user}/posts', PostController::class)->name('user.posts');
    Route::put('/post/{post}', UpdateController::class)->name('post.update');
    Route::delete('/post/{post}', DeleteController::class)->name('post.delete');
    Route::post('/upload-image', UploadImageController::class);

    // Platforms
    Route::prefix('platforms')->group(function () {
        Route::get('/', ListController::class);
        Route::post('/', \App\Http\Controllers\Api\Platform\StoreController::class);
        Route::delete('/{platform}', \App\Http\Controllers\Api\Platform\DeleteController::class);
        Route::put('/{platform}/toggle', ToggleController::class)->name('platforms.toggle');
    });

    // Activity
    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
});