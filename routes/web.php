<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\CargaHorariaController;
use App\Http\Controllers\AprobacionController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\AsistenciaController;
use Illuminate\Support\Facades\App;

// Autenticación
Route::middleware('web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::view('/login/select', 'auth.select-profile')->name('login.select');
    Route::view('/login/usuario', 'auth.select-usuario-rol')->name('login.select.usuario');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Recuperación de contraseña (modo desarrollo: muestra link en pantalla)
    Route::get('/password/forgot', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::view('/password/sent', 'auth.passwords.sent')->name('password.sent');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

    // Dashboard protegido
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware('auth_simple')->name('dashboard');

    // CU2: Gestionar Docentes
    Route::middleware('auth_simple')->group(function () {
        Route::resource('docentes', DocenteController::class)->except(['show']);
        Route::resource('materias', MateriaController::class)->except(['show']);
        Route::resource('grupos', GrupoController::class)->except(['show']);
        Route::get('grupos/{grupo}/docentes', [GrupoController::class, 'docentes'])->name('grupos.docentes');
        Route::post('grupos/{grupo}/docentes', [GrupoController::class, 'addDocente'])->name('grupos.docentes.add');
        Route::delete('grupos/{grupo}/docentes/{dmg}', [GrupoController::class, 'removeDocente'])->name('grupos.docentes.remove');

        // CU5: Gestionar Aulas
        Route::resource('aulas', AulaController::class)->except(['show']);

        // CU6: Asignar Carga Horaria (usa tabla 'horarios')
        Route::resource('carga', CargaHorariaController::class)->parameters(['carga' => 'cargum'])->except(['show']);

        // CU7: Aprobar / Rechazar Asignaciones
        Route::get('aprobaciones', [AprobacionController::class, 'index'])->name('aprobaciones.index');
        Route::post('aprobaciones/{dmg}/aprobar', [AprobacionController::class, 'approve'])->name('aprobaciones.approve');
        Route::post('aprobaciones/{dmg}/rechazar', [AprobacionController::class, 'reject'])->name('aprobaciones.reject');

        // CU8: Gestionar Horarios (manual básico)
        Route::resource('horarios', HorarioController::class)->except(['show']);

        // CU10: Registrar Asistencia Docente
        Route::resource('asistencias', AsistenciaController::class)->except(['show']);
        Route::get('asistencias/qr/{horario}', [AsistenciaController::class, 'qr'])->name('asistencias.qr');
        Route::get('asistencias/qr-register', [AsistenciaController::class, 'qrRegister'])
            ->name('asistencias.qr.register'); // ruta firmada
    });
});

// Ruta de siembra rápida para desarrollo local (crea un usuario demo)
if (App::environment('local')) {
    Route::get('/dev/seed-user', function () {
        $usuarioModel = \App\Models\Usuario::class;
        $exists = $usuarioModel::where('correo', 'admin@example.com')->exists();
        if (!$exists) {
            $usuarioModel::create([
                'nombre' => 'Administrador',
                'apellido' => 'Sistema',
                'correo' => 'admin@example.com',
                'contrasena' => 'secret123',
                'telefono' => '70000000',
                'activo' => true,
            ]);
        }
        return 'Usuario de desarrollo listo (usuarios): admin@example.com / secret123';
    });
}
