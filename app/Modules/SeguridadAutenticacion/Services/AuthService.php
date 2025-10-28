<?php

namespace App\Modules\SeguridadAutenticacion\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Servicio de autenticación (borrador) para centralizar lógica de login/logout.
 * Nota: Actualmente el proyecto usa App\Http\Controllers\AuthController directamente.
 * Este servicio es una capa opcional para futuras refactorizaciones por paquetes.
 */
class AuthService
{
    public function verificarCredenciales(string $correo, string $contrasena): ?User
    {
        $user = User::where('correo', $correo)->first();
        if (!$user) return null;

        $stored = $user->contrasena;
        $isHashed = password_get_info($stored)['algo'] !== 0;
        if ($isHashed) {
            if (!Hash::check($contrasena, $stored)) return null;
        } else {
            if (!hash_equals($stored, $contrasena)) return null;
        }
        return $user;
    }
}

