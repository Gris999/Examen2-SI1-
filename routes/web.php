<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rutas web únicamente. Las rutas API están en routes/api.php

// Vistas de PRUEBA solo disponibles en entorno local
if (app()->environment('local')) {
    // Estas rutas exponen formularios simples para probar los endpoints
    // ⚠️ Eliminar antes de subir a producción
    Route::view('/login-test', 'login-test');
    Route::view('/recuperar-test', 'recuperar-test');
    Route::view('/reset-test', 'reset-test');
}

// Vista de restablecimiento usada por el enlace del correo
// Puede mantenerse en cualquier entorno, pero es opcional si tu frontend ya maneja el flujo.
Route::get('/reset-password', function () {
    return view('auth.reset');
})->name('password.reset');
