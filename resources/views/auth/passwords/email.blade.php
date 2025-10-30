@php($title = 'Recuperar contraseña')
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header">Recuperar contraseña</div>
      <div class="card-body">
        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
          </div>
          <button class="btn btn-primary" type="submit">Enviar enlace</button>
        </form>

        @if (session('dev_link'))
          <div class="alert alert-info mt-3">
            <div><b>Modo desarrollo:</b> usa este enlace directo para resetear:</div>
            <div><a href="{{ session('dev_link') }}">{{ session('dev_link') }}</a></div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

