<?php

use App\Http\Controllers\KeywordController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Task routes
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::patch('/tasks/{id}/toggle', [TaskController::class, 'toggle']);

// Keyword routes
Route::get('/keywords', [KeywordController::class, 'index']);
Route::post('/keywords', [KeywordController::class, 'store']);
Route::delete('/keywords/{id}', [KeywordController::class, 'destroy']);
