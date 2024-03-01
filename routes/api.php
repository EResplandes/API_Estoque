<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransferController;
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

Route::prefix('v1')->group(function () {

    // Rotas de Autenticação
    Route::prefix('/authentication')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/check', [AuthController::class, 'check']);
        Route::post('/first', [AuthController::class, 'first'])->middleware('jwt.auth');
    });

    // Rotas do Módulo de Administrador
    Route::prefix('/administrator')->middleware('jwt.auth')->group(function () {
        Route::get('/search', [UserController::class, 'getAll']);
        Route::post('/registration', [UserController::class, 'registration']);
        Route::put('/deactivation/{id}', [UserController::class, 'deactivation'])->middleware('validate.id');
        Route::put('/activate/{id}', [UserController::class, 'activate'])->middleware('validate.id');
    });

    // Rotas do Módulo de Almoxarifado
    Route::prefix('/stock')->middleware('jwt.auth')->group(function () {
        Route::get('/search', [StockController::class, 'getAll']);
        Route::get('/mystock/{id}', [StockController::class, 'getMy']);
        Route::get('/category', [StockController::class, 'getCategory']);
        Route::post('/registration', [StockController::class, 'registration']);
        Route::post('/filter/{id}', [StockController::class, 'filter'])->middleware('validate.id');
        Route::put('/approval/{id}', [StockController::class, 'approval'])->middleware(['validate.disapproval', 'validate.id']);
        Route::put('/disapprove/{id}', [StockController::class, 'disapprove'])->middleware(['validate.aapproval', 'validate.id']);
        Route::put('/add/quantity/{id}', [StockController::class, 'addQuantity'])->middleware('validate.id');
        Route::get('/search/history/{id}', [StockController::class, 'searchHistory'])->middleware('validate.id');
    });

    // Rotas do Módulo de Pedidos
    Route::prefix('/requests')->middleware('jwt.auth')->group(function () {
        Route::get('/search/{id}', [RequestsController::class, 'search']);
        Route::get('/searchAll/{id}', [RequestsController::class, 'searchWarehouse'])->middleware('validate.id');
        Route::get('/products/{id}', [RequestsController::class, 'getProducts'])->middleware('validate.id');
        Route::post('/registration', [RequestsController::class, 'registration']);
    });
    Route::get('/pdf/{id}', [RequestsController::class, 'pdf'])->middleware('validate.id');


    // Rotas do Módulo de Transfêrencia de Estoque
    Route::prefix('/transfer')->middleware('jwt.auth')->group(function () {
        Route::post('/solicitation', [TransferController::class, 'solicitation']);
        Route::put('/approval/{id}/{fk_companie}', [TransferController::class, 'approval']);
        Route::put('/disapproval/{id}', [TransferController::class, 'disapproval']);
        Route::get('/solicitations/{id}', [TransferController::class, 'mysolicitations']);
        Route::get('/solicitations/forme/{id}', [TransferController::class, 'requestForMe']);
        Route::get('/exists/{id}', [TransferController::class, 'checkRequest']);
    });

    // Rotas de Relatórios
    Route::prefix('/reports')->group(function () {
        Route::get('/stock/{company?}/{category?}', [ReportsController::class, 'stock']);
        Route::post('/requests', [ReportsController::class, 'requests']);
    });
});
