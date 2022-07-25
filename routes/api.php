<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\TaskManagerController;
use App\Http\Middleware\ValidateJsonApiDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

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

Route::name('api.v1.')->group(function (){
    Route::apiResource('tasks', TaskController::class);


    Route::apiResource('managers', ManagerController::class)
        ->only(['index', 'show']);

    Route::get('tasks/{task}/relationships/manager', [
        TaskManagerController::class, 'index'
    ])->name('tasks.relationships.manager');

    Route::patch('tasks/{task}/relationships/manager', [
        TaskManagerController::class, 'update'
    ])->name('tasks.relationships.manager');

    Route::get('tasks/{task}/manager', [
        TaskManagerController::class, 'show'
    ])->name('tasks.manager');

    Route::withoutMiddleware(ValidateJsonApiDocument::class)
        ->post('login', LoginController::class)
        ->name('login');

    Route::withoutMiddleware(ValidateJsonApiDocument::class)
        ->post('register', RegisterController::class)
        ->name('register');

});

