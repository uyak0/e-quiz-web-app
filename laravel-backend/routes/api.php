<?php

use App\Http\Controllers\AssignmentsController;
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
use App\Models\UserQuizAnswers;

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
    Route::put('/update-desc', [ClassroomsController::class, 'updateDescription']);
    Route::put('/update-name', [ClassroomsController::class, 'updateName']);
    Route::get('/quizzes/{classroomId}', [ClassroomsController::class, 'classroomQuizzes']);
    Route::get('/top-students/{classroomId}', [ClassroomsController::class, 'topStudents']);
    Route::get('/data/{classroomId}', [ClassroomsController::class, 'getClassroomData']);
    Route::get('/users/{classroomId}', [ClassroomsController::class, 'getClassroomUsers']);
});

Route::group(['prefix' => 'user'], function() {
    Route::get('/', [UserController::class, 'getUser']);
    Route::get('/role', [UserController::class, 'getUserRole']);
    Route::get('/classrooms/{id?}', [ClassroomsController::class, 'userClassrooms']);
    Route::get('/in-classroom', [UserController::class, 'inClassroom']);
    Route::post('/mode', [UserController::class, 'updateMode'])->name('user.updateMode')->middleware('auth');
});

Route::group(['prefix' => 'auth'], function() {
    Route::get('/check-authentication', [UserController::class, 'isAuthenticated']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::group(['prefix' => 'student'], function() {
    Route::put('{userId}/classroom-join/{classroomId}', [ClassroomsController::class, 'joinClassroom']);
    Route::get('/points', [StudentsController::class, 'getPoints']);
});

Route::group(['prefix' => 'quiz'], function() {
    Route::get('/', [QuizzesController::class, 'index']);
    Route::post('/create', [QuizzesController::class, 'store']);
    Route::post('/answer-submit', [UserQuizAnswersController::class, 'store']);
    Route::get('/answer-get', [UserQuizAnswersController::class, 'get']);
    Route::post('/reward-points', [UserQuizAnswersController::class, 'rewardPoints']);
});

Route::group(['prefix' => 'assignment'], function() {
    Route::get('/', [AssignmentsController::class, 'index']);
    Route::post('/create', [AssignmentsController::class, 'store']);
});

Route::get("online-users", [UserController::class, "getOnlineUsers"]);

Route::group(['prefix' => 'messages'], function() {
    Route::get ("/", [ChatController::class, "index"]);
    Route::post ("/", [ChatController::class, "store"]) ->name("message.store");
    Route::put ("/{id}", [ChatController::class, "update"]);
    Route::post("/upload", [ChatController::class, "upload"]);
    Route::post("/upload-document", [ChatController::class, "uploadDocument"]);
});

