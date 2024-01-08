<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::prefix('v1')->group(function() {

    // Rotas de Autenticação
    Route::prefix('/authentication')->group(function(){
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
    
    // Rotas do Módulo de Administrador
    Route::prefix('/administrator')->middleware('jwt.auth')->group(function(){
        Route::get('/search', [UserController::class, 'getAll']);
        Route::post('/registration', [UserController::class, 'registration']);
        Route::put('/deactivation/{id}', [UserController::class, 'deactivation']);
        Route::put('/activate/{id}', [UserController::class, 'activate']);
    });

});
