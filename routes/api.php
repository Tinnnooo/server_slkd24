<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/registration', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/captcha/requestToken', [CaptchaController::class, 'requestToken']);
    Route::get('/captcha/{token}', [CaptchaController::class, 'generate']);
    Route::post('/captcha/verify', [CaptchaController::class, 'validate']);
});


Route::middleware(['auth:sanctum',])->prefix('v1')->group(function () {
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::get('/me', [UserController::class, 'me']);

    Route::get('/comments/{id}', [BlogController::class, 'getComments']);

    Route::post('/comment/{id}', [BlogController::class, 'createComment']);
    Route::put('/comment/{id}', [BlogController::class, 'updateComment']);
    Route::delete('/comment/{id}', [BlogController::class, 'deleteComment']);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('v1')->group(function () {

    Route::get('/banner', [BannerController::class, 'get']);
    Route::post('/banner', [BannerController::class, 'create']);
    Route::put('/banner/{id}', [BannerController::class, 'update']);
    Route::delete('/banner/{id}', [BannerController::class, 'delete']);

    Route::get('/blog', [BlogController::class, 'get']);
    Route::post('/blog', [BlogController::class, 'create']);
    Route::put('/blog/{id}', [BlogController::class, 'update']);
    Route::delete('/blog/{id}', [BlogController::class, 'delete']);
});
