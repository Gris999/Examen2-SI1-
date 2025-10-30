@php($title = 'Editar Aula')
@extends('layouts.app')

@section('content')
<h3 class="mb-3">Editar Aula</h3>

<form method="POST" action="{{ route('aulas.update', $aula) }}" class="row g-3">
  @csrf
  @method('PUT')
  <div class="col-md-3">
    <label class="form-label">Código</label>
    <input type="text" name="codigo" value="{{ old('codigo', $aula->codigo) }}" class="form-control" maxlength="50">
  </div>
  <div class="col-md-5">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre', $aula->nombre) }}" class="form-control" maxlength="120" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Tipo</label>
    <select name="tipo" class="form-select">
      <option value="">Sin especificar</option>
      @foreach($tipos as $t)
        <option value="{{ $t }}" @selected(old('tipo', $aula->tipo)===$t)>{{ $t }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Capacidad</label>
    <input type="number" name="capacidad" value="{{ old('capacidad', $aula->capacidad) }}" class="form-control" min="1" placeholder="Requerida salvo VIRTUAL">
  </div>
  <div class="col-md-9">
    <label class="form-label">Ubicación</label>
    <input type="text" name="ubicacion" value="{{ old('ubicacion', $aula->ubicacion) }}" class="form-control">
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Actualizar</button>
    <a href="{{ route('aulas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
@endsection

