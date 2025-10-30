@php($title = 'Materias')
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Materias</h3>
  <a href="{{ route('materias.create') }}" class="btn btn-primary">Nueva Materia</a>
</div>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-4">
    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre, código o descripción...">
  </div>
  <div class="col-md-3">
    <select name="facultad_id" class="form-select" onchange="this.form.submit()">
      <option value="">Todas las Facultades</option>
      @foreach($facultades as $f)
        <option value="{{ $f->id_facultad }}" @selected(($facultadId ?? null)==$f->id_facultad)>
          {{ $f->nombre }} @if($f->sigla) ({{ $f->sigla }}) @endif
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <select name="carrera_id" class="form-select" onchange="this.form.submit()">
      <option value="">Todas las Carreras</option>
      @foreach($carreras as $c)
        <option value="{{ $c->id_carrera }}" @selected(($carreraId ?? null)==$c->id_carrera)>
          {{ $c->nombre }} @if($c->sigla) ({{ $c->sigla }}) @endif
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100" type="submit">Filtrar</button>
  </div>
</form>

@if ($materias->count() === 0)
  <div class="alert alert-info">No hay materias registradas.</div>
@else
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Código</th>
          <th>Carga Horaria</th>
          <th>Carrera</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($materias as $m)
        <tr>
          <td>{{ $m->id_materia }}</td>
          <td>{{ $m->nombre }}</td>
          <td>{{ $m->codigo }}</td>
          <td>{{ $m->carga_horaria }}</td>
          <td>{{ $m->carrera->nombre ?? '' }} @if($m->carrera?->sigla) ({{ $m->carrera->sigla }}) @endif</td>
          <td class="text-end">
            <a href="{{ route('materias.edit', $m) }}" class="btn btn-sm btn-outline-primary">Editar</a>
            <form action="{{ route('materias.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta materia?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Eliminar</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div>
    {{ $materias->links() }}
  </div>
@endif
@endsection
