<?php

use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\TasksController;
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


Route::post('/logout', [AuthUserController::class, 'logout']);
Route::post('/validate-token', [AuthUserController::class, 'validateToken']);

Route::post('/register', [AuthUserController::class, 'store']);
Route::post('/login', [AuthUserController::class, 'login']);

Route::get('/tasks', [TasksController::class, 'index']);
Route::post('/tasks', [TasksController::class, 'store']);
Route::get('/tasks/{id}', [TasksController::class, 'show']);
Route::put('/tasks/{id}', [TasksController::class, 'update']);
Route::patch('/tasks/{id}', [TasksController::class, 'updateStatus']);
Route::delete('/tasks/{id}', [TasksController::class, 'destroy']);
Route::put('/tasks/{id}/status', [TasksController::class, 'updateStatus']);
Route::get('/deleted/tasks', [TasksController::class, 'getAllTasksDeleted']);

Route::get('/users/{id}', [AuthUserController::class, 'show']);
Route::put('/users/{id}', [AuthUserController::class, 'update']);
Route::delete('/users/{id}', [AuthUserController::class, 'destroy']);
Route::get('/tasks/teleted/{id}', [TasksController::class, 'taskDeleted']);
Route::put('/tasks/{id}/restore', [TasksController::class, 'restoreTask']);
