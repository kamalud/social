<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FollowersController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/request-password-request', 'PasswordRequesst');
        Route::post('/reset-password', 'resetPassword');

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::middleware(['ability:user,admin'])->group(function () {
                Route::get('/profile', 'profile');
                Route::post('/change-password', 'changePassword');
                Route::post('/update-profile', 'updateProfile');
                Route::get('/logout', 'logout');
            });
        });
    });
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['ability:user'])->group(function () {
        Route::prefix('user')->group(function () {
            Route::apiResources([
                'posts'=> PostController::class,
                'comments'=> CommentController::class,
                'like-unlike'=> LikeController::class,
                'follow-unfollow'=> FollowersController::class,
            ]);
        });
    });
});
