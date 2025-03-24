<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomFieldController;
use App\Http\Controllers\Api\CustomFieldValueController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);   



// 4|VA4VPIMufB117wl0rI1sJsEvarSkWoxta7Yv81mdb55f373d

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class);

    Route::post('/projects/{project}/users', [ProjectController::class, 'addUser']);
    Route::delete('/projects/{project}/users/{user}', [ProjectController::class, 'removeUser']);

    // Задачи в рамках проектов
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
    Route::get('/projects/{project}/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/projects/{project}/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/projects/{project}/tasks/{task}', [TaskController::class, 'destroy']);

    // Кастомные поля
    Route::get('/tasks/{task}/custom-fields', [CustomFieldController::class, 'index']);
    Route::post('/tasks/{task}/custom-fields', [CustomFieldController::class, 'store']);
    Route::put('/tasks/{task}/custom-fields/{customField}', [CustomFieldController::class, 'update']);
    Route::delete('/tasks/{task}/custom-fields/{customField}', [CustomFieldController::class, 'destroy']);

    // Значения кастомных полей
    Route::get('/tasks/{task}/custom-field-values', [CustomFieldValueController::class, 'index']);
    Route::post('/tasks/{task}/custom-fields/{customField}/value', [CustomFieldValueController::class, 'storeOrUpdate']);
    Route::delete('/tasks/{task}/custom-fields/{customField}/value/{customFieldValue}', [CustomFieldValueController::class, 'destroy']);

});


// // Защищённые маршруты
// Route::middleware('auth:api')->group(function () {
    
//     // Текущий пользователь
//     Route::get('/user', [AuthController::class, 'user']);

//     // Проекты
//     Route::apiResource('projects', ProjectController::class);
    
//     // Задачи
//     Route::apiResource('tasks', TaskController::class);

//     // Пользователи (например, список участников проекта)
//     Route::get('/users', [UserController::class, 'index']);

//     // Кастомные поля (если нужно на фронт)
//     Route::get('/custom-fields/{entity_type}', [CustomFieldController::class, 'index']);

// });