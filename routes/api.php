<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\PortfolioController;
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

    Route::post('/comments/{id}', [BlogController::class, 'createComment']);
    Route::put('/comments/{id}', [BlogController::class, 'updateComment']);
    Route::delete('/comments/{id}', [BlogController::class, 'deleteComment']);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('v1')->group(function () {

    Route::get('/banners', [BannerController::class, 'get']);
    Route::post('/banners', [BannerController::class, 'create']);
    Route::put('/banners/{id}', [BannerController::class, 'update']);
    Route::delete('/banners/{id}', [BannerController::class, 'delete']);

    Route::get('/blogs', [BlogController::class, 'get']);
    Route::post('/blogs', [BlogController::class, 'create']);
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'delete']);

    Route::get('/portfolios', [PortfolioController::class, 'get']);
    Route::post('/portfolios', [PortfolioController::class, 'create']);
    Route::put('/portfolios/{id}', [PortfolioController::class, 'update']);
    Route::delete('/portfolios/{id}', [PortfolioController::class, 'delete']);

    // Route::get('/portfolio', [PortfolioController::class, 'get']);
    Route::post('/users', [UserController::class, 'create']);
    // Route::put('/portfolio/{id}', [PortfolioController::class, 'update']);
    // Route::delete('/portfolio/{id}', [PortfolioController::class, 'delete']);


});
