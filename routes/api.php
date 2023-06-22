<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TicketController;

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

Route::controller(UserController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});


Route::middleware('auth:sanctum')->group( function () {
    Route::prefix('user')->group(function () {
        Route::get('/',[ UserController::class, 'index']);
        Route::post('/',[ UserController::class, 'store']);
        Route::delete('/{id}',[ UserController::class, 'destroy']);
        Route::get('/{id}',[ UserController::class, 'show']);
        Route::put('/{id}',[ UserController::class, 'update']);
    });

    Route::prefix('ticket')->group(function () {
        Route::get('/',[ TicketController::class, 'index']);
        Route::post('/',[ TicketController::class, 'store']);
        Route::delete('/{id}',[ TicketController::class, 'destroy']);
        Route::get('/{id}',[ TicketController::class, 'show']);
        Route::put('/{id}',[ TicketController::class, 'update']);
    });
});


