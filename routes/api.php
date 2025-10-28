<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\SeguridadAutenticacion\Controllers\AuthController;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/recuperar', [AuthController::class, 'recuperar']);
Route::post('/reset-password', [AuthController::class, 'reset']);
