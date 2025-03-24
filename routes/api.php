<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Group all task-related routes
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']); // Get all tasks (with pagination)
    Route::get('/trashed', [TaskController::class, 'trashed']); // Get soft-deleted tasks
    Route::get('/{id}', [TaskController::class, 'show']); // Get a single task by ID
    Route::post('/', [TaskController::class, 'store']); // Create a task
    Route::put('/{id}', [TaskController::class, 'update']); // Update a task
    Route::delete('/{id}', [TaskController::class, 'destroy']); // Soft delete a task
    Route::post('/{id}/restore', [TaskController::class, 'restore']); // Restore a soft-deleted task
});
