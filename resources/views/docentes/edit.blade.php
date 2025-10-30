@php($title = 'Editar Docente')
@extends('layouts.app')

@section('content')
<h3 class="mb-3">Editar Docente</h3>

<form method="POST" action="{{ route('docentes.update', $docente) }}" class="row g-3">
  @csrf
  @method('PUT')
  <div class="col-md-6">
    <label class="form-label">Correo del Usuario</label>
    <input type="email" name="correo" value="{{ old('correo', $docente->usuario->correo ?? '') }}" class="form-control" required>
    <div class="form-text">Debe existir en la tabla usuarios y no estar asignado a otro docente.</div>
  </div>
  <div class="col-md-3">
    <label class="form-label">Código Docente</label>
    <input type="text" name="codigo_docente" value="{{ old('codigo_docente', $docente->codigo_docente) }}" class="form-control">
  </div>
  <div class="col-md-6">
    <label class="form-label">Profesión</label>
    <input type="text" name="profesion" value="{{ old('profesion', $docente->profesion) }}" class="form-control">
  </div>
  <div class="col-md-6">
    <label class="form-label">Grado Académico</label>
    <input type="text" name="grado_academico" value="{{ old('grado_academico', $docente->grado_academico) }}" class="form-control">
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Actualizar</button>
    <a href="{{ route('docentes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
@endsection

