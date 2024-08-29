<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/registration', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware(['auth:sanctum',])->prefix('v1')->group(function () {
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/get-comments/{id}', [BlogController::class, 'getComments']);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('v1')->group(function () {
    Route::post('/blog', [BlogController::class, 'create']);
});
