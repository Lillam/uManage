<?php

use Illuminate\Support\Facades\Route;

/*
|-----------------------------------------------------------------------------------------------------------------------
| API Routes
|-----------------------------------------------------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These routes are loaded by the RouteServiceProvider
| within a group which is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/user/login', [\App\Http\Controllers\Api\User\UserController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/projects', [\App\Http\Controllers\Api\Project\ProjectController::class, 'view']);
    Route::post('/projects/create', [\App\Http\Controllers\Api\Project\ProjectController::class, 'create']);
    Route::get('/users', [\App\Http\Controllers\Api\User\UserController::class, 'view']);
});


//2|MQKKp4QX9UhNXnuco0O17KLXthZ0wJe9sVWHKp89