@php($title = 'Nuevo Grupo')
@extends('layouts.app')

@section('content')
<h3 class="mb-3">Nuevo Grupo</h3>

<form method="POST" action="{{ route('grupos.store') }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Gestión</label>
    <select name="id_gestion" class="form-select" required>
      <option value="">Seleccione...</option>
      @foreach($gestiones as $g)
        <option value="{{ $g->id_gestion }}" @selected(old('id_gestion')==$g->id_gestion)>
          {{ $g->codigo }} @if($g->activo) (Activa) @endif
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Materia</label>
    <select name="id_materia" class="form-select" required>
      <option value="">Seleccione...</option>
      @foreach($materias as $m)
        <option value="{{ $m->id_materia }}" @selected(old('id_materia')==$m->id_materia)>
          {{ $m->nombre }} @if($m->codigo) ({{ $m->codigo }}) @endif
        </option>
      @endforeach
    </select>
    <div class="form-text">Puedes filtrar por facultad/carrera usando el listado general.</div>
  </div>
  <div class="col-md-2">
    <label class="form-label">Nombre Grupo</label>
    <input type="text" name="nombre_grupo" value="{{ old('nombre_grupo') }}" class="form-control" maxlength="10" required>
  </div>
  <div class="col-md-2">
    <label class="form-label">Cupo</label>
    <input type="number" name="cupo" value="{{ old('cupo') }}" class="form-control" min="1">
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Guardar</button>
    <a href="{{ route('grupos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
@endsection

