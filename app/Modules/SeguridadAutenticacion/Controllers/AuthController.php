<?php

namespace App\Modules\SeguridadAutenticacion\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller; // base controller
use App\Models\User; // usamos el modelo actual configurado en auth

class AuthController extends Controller
{
    // POST /api/login
    public function login(Request $request)
    {
        $request->validate([
            'correo'     => 'required|email',
            'contrasena' => 'required',
            'tipo'       => 'nullable|string', // opcional: "usuario" | "docente"
        ]);

        $key = 'login:'.Str::lower($request->correo);
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'error' => 'Demasiados intentos. Inténtalo de nuevo en '.ceil($seconds).'s'
            ], 429);
        }

        $user = User::where('correo', $request->correo)->first();
        if (!$user) {
            RateLimiter::hit($key, 15 * 60);
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        $raw    = $request->contrasena;
        $stored = $user->contrasena;

        $isHashed = password_get_info($stored)['algo'] !== 0;
        if ($isHashed) {
            if (!Hash::check($raw, $stored)) {
                RateLimiter::hit($key, 15 * 60);
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }
            if (Hash::needsRehash($stored)) {
                $user->contrasena = Hash::make($raw);
                $user->save();
            }
        } else {
            if (!hash_equals($stored, $raw)) {
                RateLimiter::hit($key, 15 * 60);
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }
            $user->contrasena = Hash::make($raw);
            $user->save();
        }

        if (!$user->activo) {
            return response()->json(['error' => 'Cuenta inactiva'], 403);
        }

        $roles = $user->roles()->pluck('nombre');
        if ($roles->isEmpty()) {
            return response()->json(['error' => 'Usuario sin rol asignado'], 403);
        }

        RateLimiter::clear($key);

        $token = $user->createToken('api-token')->plainTextToken;

        $role  = Str::lower($roles->first());
        $panel = match ($role) {
            'administrador' => '/panel/admin',
            'docente'       => '/panel/docente',
            'director'      => '/panel/director',
            default         => '/panel'
        };

        return response()->json([
            'token'   => $token,
            'usuario' => $user,
            'roles'   => $roles,
            'panel'   => $panel,
        ]);
    }

    // POST /api/logout (auth:sanctum)
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    // POST /api/recuperar
    public function recuperar(Request $request)
    {
        $request->validate(['correo' => 'required|email']);

        $user = User::where('correo', $request->correo)->first();
        if (!$user) return response()->json(['error' => 'Usuario no encontrado'], 404);

        $token = Str::random(64);

        DB::table(config('auth.passwords.users.table', 'password_reset_tokens'))
            ->updateOrInsert(
                ['email' => $user->correo],
                ['token' => hash('sha256', $token), 'created_at' => now()]
            );

        $link = url('/reset-password?token='.$token.'&email='.urlencode($user->correo));

        Mail::raw('Recupera tu contraseña aquí (válido 24h): '.$link, function ($msg) use ($user) {
            $msg->to($user->correo)->subject('Recuperación de contraseña');
        });

        return response()->json(['message' => 'Correo enviado']);
    }

    // POST /api/reset-password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'contrasena' => 'required|min:6|confirmed'
        ]);

        $record = DB::table(config('auth.passwords.users.table', 'password_reset_tokens'))
            ->where('email', $request->email)
            ->first();

        if (!$record) return response()->json(['error' => 'Token inválido o expirado'], 400);

        $isValid = hash_equals($record->token, hash('sha256', $request->token));
        $isFresh = Carbon::parse($record->created_at)->gt(now()->subHours(24));
        if (!$isValid || !$isFresh) {
            return response()->json(['error' => 'Token inválido o expirado'], 400);
        }

        $user = User::where('correo', $request->email)->first();
        if (!$user) return response()->json(['error' => 'Usuario no encontrado'], 404);

        $user->contrasena = Hash::make($request->contrasena);
        $user->save();

        DB::table(config('auth.passwords.users.table', 'password_reset_tokens'))
            ->where('email', $request->email)
            ->delete();

        return response()->json(['message' => 'Contraseña actualizada correctamente']);
    }
}

