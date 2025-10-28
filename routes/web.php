<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route for testing login page
Route::get('/login-test', function () {
    return view('auth.login');
});

// Keep web routes here. Also register API prefixed routes but using the 'api' middleware
Route::middleware('api')->prefix('api')->group(function () {
	Route::post('/login', [AuthController::class, 'login']);
	Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
	Route::post('/recuperar', [AuthController::class, 'recuperar']);
	Route::post('/reset-password', [AuthController::class, 'reset']);
});
