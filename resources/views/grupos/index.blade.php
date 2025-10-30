@php($title = 'Grupos')
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Grupos</h3>
  <a href="{{ route('grupos.create') }}" class="btn btn-primary">Nuevo Grupo</a>
</div>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-3">
    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por grupo o materia...">
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
    <select name="gestion_id" class="form-select" onchange="this.form.submit()">
      <option value="">Todas las Gestiones</option>
      @foreach($gestiones as $g)
        <option value="{{ $g->id_gestion }}" @selected(($gestionId ?? null)==$g->id_gestion)>{{ $g->codigo }} @if($g->activo) (Activa) @endif</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-1">
    <button class="btn btn-outline-secondary w-100" type="submit">Filtrar</button>
  </div>
</form>

@if ($grupos->count() === 0)
  <div class="alert alert-info">No hay grupos registrados.</div>
@else
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Grupo</th>
          <th>Materia</th>
          <th>Gestión</th>
          <th>Cupo</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($grupos as $g)
        <tr>
          <td>{{ $g->id_grupo }}</td>
          <td>{{ $g->nombre_grupo }}</td>
          <td>{{ $g->materia->nombre ?? '' }} @if($g->materia?->codigo) ({{ $g->materia->codigo }}) @endif</td>
          <td>{{ $g->gestion->codigo ?? '' }}</td>
          <td>{{ $g->cupo }}</td>
          <td class="text-end">
            <a href="{{ route('grupos.docentes', $g) }}" class="btn btn-sm btn-outline-secondary">Docentes</a>
            <a href="{{ route('grupos.edit', $g) }}" class="btn btn-sm btn-outline-primary">Editar</a>
            <form action="{{ route('grupos.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este grupo?');">
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
    {{ $grupos->links() }}
  </div>
@endif
@endsection

