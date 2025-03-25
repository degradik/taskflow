<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomFieldController;

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
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);



// 4|VA4VPIMufB117wl0rI1sJsEvarSkWoxta7Yv81mdb55f373d

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class);

    Route::post('/projects/{project}/users', [ProjectController::class, 'addUser']);
    Route::delete('/projects/{project}/users/{user}', [ProjectController::class, 'removeUser']);
    
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