<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

// Public routes for registration and login
Route::post('/register', [AuthController::class, 'register']);  // Register a new user
Route::post('/login', [AuthController::class, 'login']);  // Login an existing user

// Routes that require authentication via Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Fetch the authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Task-related routes for the authenticated user
    Route::get('/tasks', [TaskController::class, 'index']);  // Get all tasks for the authenticated user
    Route::post('/tasks', [TaskController::class, 'store']);  // Store a new task for the authenticated user
    Route::patch('/tasks/{id}', [TaskController::class, 'update']);  // Update a task for the authenticated user
    Route::patch('/tasks/{id}/edit', [TaskController::class, 'editTask']);  // Edit the task text for the authenticated user
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);  // Delete a task for the authenticated user
});