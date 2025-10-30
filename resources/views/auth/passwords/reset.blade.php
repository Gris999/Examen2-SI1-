@php($title = 'Restablecer contraseña')
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header">Restablecer contraseña</div>
      <div class="card-body">
        <form method="POST" action="{{ route('password.update') }}">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">
          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
          <button class="btn btn-primary" type="submit">Actualizar contraseña</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

