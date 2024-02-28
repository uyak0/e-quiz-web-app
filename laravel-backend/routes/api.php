<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClassroomsController;

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

Route::get('/students', [StudentsController::class, 'index']);

Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::get('/classrooms/{id?}', [ClassroomsController::class, 'index']);
