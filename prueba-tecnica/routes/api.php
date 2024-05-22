<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CuentaController;
use App\Http\Controllers\Api\transaccionesController;
use App\Http\Controllers\Api\ClienteController;

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


Route::post('/login', [AuthController::class, "login"]);
Route::post('/register',[AuthController::class, "register"]);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/test', function(){
        return response(["Test"=>"test ok"]);
    });
    Route::post('/logout', [AuthController::class, "logOut"]);
    Route::get('/showAll', [AuthController::class, "allUser"]);
    Route::get('/userProfile', [AuthController::class, "userProfile"]);
    Route::get('/cliente/{cedula}',[ClienteController::class,"search"]);
    Route::post('/cliente',[ClienteController::class,"store"]);
    Route::post('/cuenta', [CuentaController::class, "store"]);
    Route::post('/cuenta/todos', [CuentaController::class, "show"]);
    Route::post('/cuenta/{cuenta}', [CuentaController::class, "show"]);
    Route::get('/cuenta/{cuenta}', [CuentaController::class, "consultaSaldo"]);
    Route::post('/transaccion', [transaccionesController::class, "store"]);
    Route::get('/transaccion/{id}',[transaccionesController::class,"show"]);
   
});


