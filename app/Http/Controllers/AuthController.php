<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // LOGIN
    public function login(Request $request)
{
    $request->validate([
        'correo'     => 'required|email',
        'contrasena' => 'required',
    ]);

    $user = User::where('correo', $request->correo)->first();

    if (!$user) {
        return response()->json(['error' => 'Credenciales incorrectas'], 401);
    }

    $raw     = $request->contrasena;
    $stored  = $user->contrasena;

    // Detectar si lo guardado ya es un hash reconocido por password_* (bcrypt/argon)
    $isHashed = password_get_info($stored)['algo'] !== 0;

    if ($isHashed) {
        // Caso normal: ya está hasheada → verificar
        if (!Hash::check($raw, $stored)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // (Opcional) subir factor de costo/algoritmo cuando corresponda
        if (Hash::needsRehash($stored)) {
            $user->contrasena = Hash::make($raw);
            $user->save();
        }
    } else {
        // Valor en texto plano en la BD → solo hashear si coincide EXACTO con lo que escribió
        if (!hash_equals($stored, $raw)) { // evita timing attacks
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // Coincide: migramos a hash de forma transparente
        $user->contrasena = Hash::make($raw);
        $user->save();
    }

    if (!$user->activo) {
        return response()->json(['error' => 'Cuenta inactiva'], 403);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token'  => $token,
        'usuario'=> $user,
        'roles'  => $user->roles()->pluck('nombre'),
    ]);
}

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    // RECUPERAR CONTRASEÑA
    public function recuperar(Request $request)
    {
        $request->validate(['correo' => 'required|email']);

        $user = User::where('correo', $request->correo)->first();
        if (!$user) return response()->json(['error' => 'Usuario no encontrado'], 404);

        $token = Str::random(60);
        $user->remember_token = $token;
        $user->save();

        $link = url("/reset-password?token={$token}");

        Mail::raw("Recupera tu contraseña aquí: $link", function ($msg) use ($user) {
            $msg->to($user->correo)->subject('Recuperación de contraseña');
        });

        return response()->json(['message' => 'Correo enviado']);
    }

    // RESTABLECER CONTRASEÑA
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'contrasena' => 'required|min:6|confirmed'
        ]);

        $user = User::where('remember_token', $request->token)->first();
        if (!$user) return response()->json(['error' => 'Token inválido o expirado'], 400);

        $user->contrasena = Hash::make($request->contrasena);
        $user->remember_token = null;
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente']);
    }
}

