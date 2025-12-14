<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AuthController;

// Test simple pour voir si l'API répond
Route::get('/ping', function () {
    return response()->json(['message' => 'Pong! API is working']);
});

// Routes Publiques
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);
Route::post('/contact', [ContactController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

// Routes Admin
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::get('/messages', [ContactController::class, 'index']);
});