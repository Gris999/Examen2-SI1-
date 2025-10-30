@php($title = 'Nueva Materia')
@extends('layouts.app')

@section('content')
<h3 class="mb-3">Nueva Materia</h3>

<form method="POST" action="{{ route('materias.store') }}" class="row g-3">
  @csrf
  <div class="col-md-6">
    <label class="form-label">Carrera</label>
    <select name="id_carrera" class="form-select" required>
      <option value="">Seleccione...</option>
      @foreach($carreras as $c)
        <option value="{{ $c->id_carrera }}" @selected(old('id_carrera')==$c->id_carrera)>
          {{ $c->nombre }} @if($c->sigla) ({{ $c->sigla }}) @endif
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Código</label>
    <input type="text" name="codigo" value="{{ old('codigo') }}" class="form-control">
  </div>
  <div class="col-md-4">
    <label class="form-label">Carga Horaria</label>
    <input type="number" min="1" name="carga_horaria" value="{{ old('carga_horaria') }}" class="form-control" required>
  </div>
  <div class="col-12">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Guardar</button>
    <a href="{{ route('materias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
@endsection

