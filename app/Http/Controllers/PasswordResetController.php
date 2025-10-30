<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = Usuario::where('correo', $data['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No existe un usuario con ese correo.'])->withInput();
        }

        $plainToken = Str::random(64);
        $hashed = Hash::make($plainToken);

        DB::table(config('auth.passwords.users.table', 'password_reset_tokens'))
            ->where('email', $user->correo)
            ->delete();

        DB::table(config('auth.passwords.users.table', 'password_reset_tokens'))
            ->insert([
                'email' => $user->correo,
                'token' => $hashed,
                'created_at' => Carbon::now(),
            ]);

        $devLink = route('password.reset.form', ['token' => $plainToken, 'email' => $user->correo]);

        return redirect()->route('password.sent')
            ->with('status', 'Te enviamos un enlace para restablecer tu contraseña.')
            ->with('dev_link', $devLink)
            ->with('email', $user->correo);
    }

    public function showResetForm(Request $request, string $token)
    {
        $email = $request->query('email');
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $record = DB::table(config('auth.passwords.users.table', 'password_reset_tokens'))
            ->where('email', $data['email'])
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Token inválido o expirado.']);
        }

        // Expiración de 60 minutos
        if (Carbon::parse($record->created_at)->lt(now()->subMinutes(config('auth.passwords.users.expire', 60)))) {
            return back()->withErrors(['email' => 'El token ha expirado.']);
        }

        if (!Hash::check($data['token'], $record->token)) {
            return back()->withErrors(['token' => 'Token inválido.']);
        }

        $user = Usuario::where('correo', $data['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado.']);
        }

        $user->contrasena = $data['password'];
        $user->save();

        DB::table(config('auth.passwords.users.table', 'password_reset_tokens'))
            ->where('email', $data['email'])
            ->delete();

        // Iniciar sesión tras reset
        auth()->login($user);

        return redirect()->route('dashboard')->with('status', 'Contraseña actualizada.');
    }
}
