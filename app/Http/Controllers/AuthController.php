<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Ingrese su correo.',
            'password.required' => 'Ingrese su contraseña.',
        ]);

        $login = $validated['login'];
        $password = $validated['password'];

        // Buscar por correo en la tabla `usuarios`
        $user = Usuario::where('correo', $login)->first();
        if (!$user) {
            return back()->withErrors(['login' => 'Credenciales inválidas'])->withInput($request->only('login'));
        }

        $stored = (string) $user->contrasena;
        $isBcrypt = str_starts_with($stored, '$2y$');

        if ($isBcrypt) {
            if (Hash::check($password, $stored)) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
        } else {
            // Compatibilidad con datos heredados (texto plano o MD5)
            $plainMatch = hash_equals($stored, $password);
            $md5Match = (strlen($stored) === 32 && preg_match('/^[a-f0-9]{32}$/i', $stored))
                ? hash_equals(md5($password), $stored)
                : false;

            Log::debug('auth.fallback_check', [
                'correo' => $login,
                'user_id' => $user->id_usuario ?? null,
                'is_bcrypt' => $isBcrypt,
                'stored_len' => strlen($stored),
                'plain_match' => $plainMatch,
                'md5_match' => $md5Match,
            ]);

            if ($plainMatch || $md5Match) {
                // Rehash a bcrypt y continuar
                $user->contrasena = $password; // mutator aplicará bcrypt
                $user->save();
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors(['login' => 'Credenciales inválidas'])->withInput($request->only('login'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'Sesión cerrada.');
    }
}

