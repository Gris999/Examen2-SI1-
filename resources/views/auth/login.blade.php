@php($title = 'Iniciar sesión')
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-header">Iniciar sesión</div>
      <div class="card-body">
        <form method="POST" action="{{ route('login.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="login" value="{{ old('login') }}" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-primary" type="submit">Entrar</button>
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  @if (app()->environment('local') && Route::has('password.request'))
  <div class="col-md-5 mt-3">
    <div class="alert alert-info">
      <div><b>Modo desarrollo:</b> puedes crear un usuario de prueba visitando <code>/dev/seed-user</code>.</div>
    </div>
  </div>
  @endif
</div>
@endsection
