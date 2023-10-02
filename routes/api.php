<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TodoTaskController;
use App\Http\Controllers\CategoryController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('verify-email', [AuthController::class, 'VerifyEmail']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);



Route::group([

	'middleware' => 'api',
	'prefix' => 'auth'

], function ($router) {
	Route::prefix('index')->group(function () {
		Route::get('', [MeController::class, 'index']);
		Route::put('', [MeController::class, 'update']);
	});

	Route::prefix('posts')->group(function () {
		Route::get('', [PostController::class, 'index']);
		Route::get('public-light', [PostController::class, 'publicLight']);
		Route::get('my-posts', [PostController::class, 'myPosts']);
		Route::get('{id}', [PostController::class, 'postView']);
		// Route::put('{post}', [PostController::class, 'update']);
		Route::post('', [PostController::class, 'store']);
		// Route::delete('{post}', [PostController::class, 'destroy']);
	});

	Route::prefix('categories')->group(function () {
		Route::get('{category}', [PostController::class, 'postByCategory']);
		Route::post('', [CategoryController::class, 'store']);
	});

	Route::prefix('todos')->group(function () {
		Route::get('', [TodoController::class, 'index']);
		Route::put('{todo}', [TodoController::class, 'update']);
		Route::post('', [TodoController::class, 'store']);
		Route::delete('{todo}', [TodoController::class, 'destroy']);
	});

	Route::prefix('todo-tasks')->group(function () {
		Route::get('{id?}', [TodoTaskController::class, 'index']);
		Route::put('{todo}', [TodoTaskController::class, 'update']);
		Route::post('', [TodoTaskController::class, 'store']);
		Route::delete('{todo}', [TodoTaskController::class, 'destroy']);
	});

	Route::post('logout', [AuthController::class, 'logout']);
	Route::post('refresh', [AuthController::class, 'refresh']);
});
