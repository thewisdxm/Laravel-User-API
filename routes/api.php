<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('/user/update/{id}', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::post('/user/create', [UserController::class, 'register']);

Route::post('/user/login', [UserController::class, 'login']);

Route::delete('/user/delete/{id}', [UserController::class, 'delete'])->middleware('auth:sanctum');

Route::get('/user', [UserController::class, 'getuser'])->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'getusers'])->middleware('auth:sanctum');

Route::post('/user/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');