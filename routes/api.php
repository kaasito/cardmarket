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
    Route::post('/login',[UsuariosController::class,'login']);
    Route::post('/recuperarPass',[UsuariosController::class,'recuperarPass']);
    });


Route::middleware(['check-admin', 'check-venta'])->group(function () { 
 Route::prefix('cartas')->group(function(){
    Route::post('/crear', [CartasController::class, 'crear'])->withoutMiddleware(['check-venta']); 
    Route::post('/venta', [CartasController::class, 'venta'])->withoutMiddleware(['check-admin']); 
    Route::get('/buscarparavender', [CartasController::class, 'buscarparavender'])->withoutMiddleware(['check-admin']);
    Route::get('/buscaralaventa', [CartasController::class, 'buscaralaventa'])->withoutMiddleware(['check-admin','check-venta']);
    });
});
Route::middleware(['check-admin'])->group(function () { 
Route::prefix('colecciones')->group(function(){
        Route::post('/crear', [ColeccionsController::class, 'crear']);
    });
});