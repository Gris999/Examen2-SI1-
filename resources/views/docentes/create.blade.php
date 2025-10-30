@php($title = 'Nuevo Docente')
@extends('layouts.app')

@section('content')
<h3 class="mb-3">Nuevo Docente</h3>

<form method="POST" action="{{ route('docentes.store') }}" class="row g-3">
  @csrf
  <div class="col-md-6">
    <label class="form-label">Correo del Usuario</label>
    <input type="email" name="correo" value="{{ old('correo') }}" class="form-control" required>
    <div class="form-text">Debe existir en la tabla usuarios y no estar asignado.</div>
  </div>
  <div class="col-md-3">
    <label class="form-label">Código Docente</label>
    <input type="text" name="codigo_docente" value="{{ old('codigo_docente') }}" class="form-control">
  </div>
  <div class="col-md-6">
    <label class="form-label">Profesión</label>
    <input type="text" name="profesion" value="{{ old('profesion') }}" class="form-control">
  </div>
  <div class="col-md-6">
    <label class="form-label">Grado Académico</label>
    <input type="text" name="grado_academico" value="{{ old('grado_academico') }}" class="form-control">
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Guardar</button>
    <a href="{{ route('docentes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
@endsection

