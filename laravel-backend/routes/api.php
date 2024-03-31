<?php

use App\Http\Controllers\QuizzesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserQuizAnswersController;

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

Route::group(['prefix' => 'classroom'], function() {
    Route::get('/{id?}', [ClassroomsController::class, 'index']);
    Route::delete('/{id}', [ClassroomsController::class, 'deleteClassroom']);
    Route::post('/create', [ClassroomsController::class, 'createClassroom']);
    Route::get('/quizzes/{classroomId}', [ClassroomsController::class, 'classroomQuizzes']);
});

Route::group(['prefix' => 'user'], function() {
    Route::get('/', [UserController::class, 'getUser']);
    Route::get('/role', [UserController::class, 'getUserRole']);
    Route::get('/classrooms/{id?}', [ClassroomsController::class, 'userClassrooms']);
    Route::get('/in-classroom', [UserController::class, 'inClassroom']);
});

Route::group(['prefix' => 'auth'], function() {
    Route::get('/check-authentication', [UserController::class, 'isAuthenticated']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::group(['prefix' => 'student'], function() {
    Route::put('{userId}/classroom-join/{classroomId}', [ClassroomsController::class, 'joinClassroom']);
});

Route::group(['prefix' => 'quiz'], function() {
    Route::get('/', [QuizzesController::class, 'index']);
    Route::post('/create', [QuizzesController::class, 'store']);
    Route::post('/answer-submit', [UserQuizAnswersController::class, 'store']);
    Route::get('/answer-get', [UserQuizAnswersController::class, 'get']);
});
