@php($title = 'Asistencia')
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-0">Asistencias Docentes</h4>
    <small class="text-muted">Administra el registro de asistencias de los docentes</small>
  </div>
  <a href="{{ route('asistencias.create') }}" class="btn btn-teal">Registrar Manual</a>
</div>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-center m-0">
      <div class="col-md-3">
        <input type="date" name="desde" value="{{ $desde }}" class="form-control" placeholder="dd/mm/aaaa">
      </div>
      <div class="col-md-3">
        <input type="date" name="hasta" value="{{ $hasta }}" class="form-control" placeholder="dd/mm/aaaa">
      </div>
      <div class="col-md-3">
        <select name="docente_id" class="form-select">
          <option value="">Todos</option>
          @foreach($docentes as $d)
            <option value="{{ $d->id_docente }}" @selected(($docenteId ?? null)==$d->id_docente)>
              {{ $d->usuario->nombre ?? '' }} {{ $d->usuario->apellido ?? '' }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="estado" class="form-select">
          <option value="">Todos</option>
          @foreach(['PRESENTE','AUSENTE','RETRASO','JUSTIFICADO'] as $e)
            <option value="{{ $e }}" @selected(($estado ?? '')===$e)>{{ ucfirst(strtolower($e)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-1 d-grid">
        <button class="btn btn-teal" type="submit">Filtrar</button>
      </div>
    </form>
  </div>
</div>

@if ($asistencias->count() === 0)
  <div class="alert alert-info">No hay registros.</div>
@else
  <div class="table-responsive">
    <table class="table align-middle">
      <thead class="table-light">
        <tr>
          <th style="width:60px">#</th>
          <th>Docente</th>
          <th>Materia / Grupo / Gestión</th>
          <th>Fecha</th>
          <th>Entrada</th>
          <th>Aula</th>
          <th>Método</th>
          <th>Estado</th>
          <th class="text-end" style="width:160px">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($asistencias as $a)
          <tr>
            <td>{{ $asistencias->firstItem() + $loop->index }}</td>
            <td>{{ $a->docente->usuario->nombre ?? '' }} {{ $a->docente->usuario->apellido ?? '' }}</td>
            <td>
              <div class="fw-semibold text-uppercase">{{ $a->horario->grupo->materia->nombre ?? '' }}</div>
              <div class="text-muted small">Grupo {{ $a->horario->grupo->nombre_grupo ?? '' }} / {{ $a->horario->grupo->gestion->codigo ?? '' }}</div>
            </td>
            <td>{{ $a->fecha }}</td>
            <td>{{ $a->hora_entrada }}</td>
            <td>{{ $a->horario->aula->nombre ?? '-' }}</td>
            <td>
              @php($met = $a->metodo)
              <span class="badge bg-primary-subtle border text-primary">{{ $met }}</span>
            </td>
            <td>
              @php($st = $a->estado)
              @if($st==='PRESENTE')
                <span class="badge bg-success">PRESENTE</span>
              @elseif($st==='RETRASO')
                <span class="badge bg-warning text-dark">RETRASADO</span>
              @elseif($st==='AUSENTE')
                <span class="badge bg-danger">AUSENTE</span>
              @else
                <span class="badge bg-info text-dark">JUSTIFICADO</span>
              @endif
            </td>
            <td class="text-end">
              <a href="{{ route('asistencias.edit', $a) }}" class="btn btn-sm btn-outline-primary">Editar</a>
              <form action="{{ route('asistencias.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar asistencia?');">
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
  <div>{{ $asistencias->links('vendor.pagination.teal') }}</div>
@endif
@endsection

