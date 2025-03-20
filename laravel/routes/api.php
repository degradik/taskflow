<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\ProjectController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'user']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
});
