<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [TaskController::class, 'index'])->name('tasks.view');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::post('/update-task-priority',  [TaskController::class, 'updatePriority'])->name('tasks.update');

Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::delete('/tasks/{id}',  [TaskController::class, 'delete'])->name('tasks.delete');
