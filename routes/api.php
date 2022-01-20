<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\CartasController;
use App\Http\Controllers\ColeccionsController;

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

Route::prefix('usuarios')->group(function(){
    Route::post('/registrar', [UsuariosController::class, 'registrar']);
    Route::put('/login',[UsuariosController::class,'login']);
    });

 Route::prefix('cartas')->group(function(){
    Route::post('/crear', [CartasController::class, 'crear']);
    });

    Route::prefix('colecciones')->group(function(){
        Route::post('/crear', [ColeccionsController::class, 'crear']);
    });
