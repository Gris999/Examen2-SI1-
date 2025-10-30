@php($title = 'Carga Horaria')
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Carga Horaria</h3>
  <a href="{{ route('carga.create') }}" class="btn btn-primary">Nueva Asignación</a>
</div>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-3">
    <select name="docente_id" class="form-select" onchange="this.form.submit()">
      <option value="">Todos los docentes</option>
      @foreach($docentes as $d)
        <option value="{{ $d->id_docente }}" @selected(($docenteId ?? null)==$d->id_docente)>
          {{ $d->usuario->nombre ?? '' }} {{ $d->usuario->apellido ?? '' }} ({{ $d->usuario->correo ?? '' }})
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <select name="gestion_id" class="form-select" onchange="this.form.submit()">
      <option value="">Todas las Gestiones</option>
      @foreach($gestiones as $g)
        <option value="{{ $g->id_gestion }}" @selected(($gestionId ?? null)==$g->id_gestion)>{{ $g->codigo }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <select name="materia_id" class="form-select" onchange="this.form.submit()">
      <option value="">Todas las Materias</option>
      @foreach($materias as $m)
        <option value="{{ $m->id_materia }}" @selected(($materiaId ?? null)==$m->id_materia)>
          {{ $m->nombre }} @if($m->codigo) ({{ $m->codigo }}) @endif
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <select name="grupo_id" class="form-select" onchange="this.form.submit()">
      <option value="">Todos los Grupos</option>
      @foreach($grupos as $gr)
        <option value="{{ $gr->id_grupo }}" @selected(($grupoId ?? null)==$gr->id_grupo)>
          {{ $gr->nombre_grupo }} — {{ $gr->materia->nombre ?? '' }} / {{ $gr->gestion->codigo ?? '' }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <select name="dia" class="form-select" onchange="this.form.submit()">
      <option value="">Todos los días</option>
      @foreach($dias as $d)
        <option value="{{ $d }}" @selected(($dia ?? '')===$d)>{{ $d }}</option>
      @endforeach
    </select>
  </div>
</form>

@if ($horarios->count() === 0)
  <div class="alert alert-info">No hay asignaciones.</div>
@else
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Docente</th>
          <th>Materia / Grupo / Gestión</th>
          <th>Día</th>
          <th>Hora</th>
          <th>Aula</th>
          <th>Modalidad</th>
          <th>Estado</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($horarios as $h)
          <tr>
            <td>{{ $h->id_horario }}</td>
            <td>{{ $h->docenteMateriaGestion->docente->usuario->nombre ?? '' }} {{ $h->docenteMateriaGestion->docente->usuario->apellido ?? '' }}</td>
            <td>
              {{ $h->grupo->materia->nombre ?? '' }}
              @if($h->grupo->materia?->codigo) ({{ $h->grupo->materia->codigo }}) @endif
              — Grupo {{ $h->grupo->nombre_grupo ?? '' }} / {{ $h->grupo->gestion->codigo ?? '' }}
            </td>
            <td>{{ $h->dia }}</td>
            <td>{{ substr($h->hora_inicio,0,5) }} - {{ substr($h->hora_fin,0,5) }}</td>
            <td>{{ $h->aula->nombre ?? '—' }}</td>
            <td>{{ $h->modalidad }}</td>
            <td>
              @php($cls = $h->estado === 'APROBADA' ? 'success' : ($h->estado === 'RECHAZADA' ? 'danger' : 'warning text-dark'))
              <span class="badge bg-{{ $cls }}">{{ ucfirst(strtolower($h->estado ?? 'PENDIENTE')) }}</span>
            </td>
            <td class="text-end">
              <a href="{{ route('carga.edit', $h) }}" class="btn btn-sm btn-outline-primary">Editar</a>
              <form action="{{ route('carga.destroy', $h) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar asignación?');">
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
  <div>{{ $horarios->links() }}</div>
@endif
@endsection
