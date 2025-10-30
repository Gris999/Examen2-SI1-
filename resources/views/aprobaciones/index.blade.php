@php($title = 'Aprobaciones de Asignaciones')
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Aprobaciones de Asignaciones (Docente–Materia–Gestión)</h3>
  <a href="{{ route('grupos.index') }}" class="btn btn-outline-secondary">Asignar (CU6)</a>
</div>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-3">
    <select name="estado" class="form-select" onchange="this.form.submit()">
      @foreach(['PENDIENTE','APROBADA','RECHAZADA'] as $e)
        <option value="{{ $e }}" @selected(($estado ?? 'PENDIENTE')===$e)>{{ ucfirst(strtolower($e)) }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100" type="submit">Filtrar</button>
  </div>
</form>

@if (($asignaciones->count() ?? 0) === 0)
  <div class="alert alert-info">No hay registros para mostrar.</div>
@else
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Docente</th>
          <th>Materia / Gestión</th>
          <th>Horarios</th>
          <th>Estado</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($asignaciones as $a)
          <tr>
            <td>{{ $a->id_docente_materia_gestion }}</td>
            <td>{{ $a->docente->usuario->nombre ?? '' }} {{ $a->docente->usuario->apellido ?? '' }}</td>
            <td>
              {{ $a->materia->nombre ?? '' }}
              @if($a->materia?->codigo) ({{ $a->materia->codigo }}) @endif
              — {{ $a->gestion->codigo ?? '' }}
            </td>
            <td>{{ $a->horarios_count }}</td>
            <td>
              @php($cls = $a->estado === 'APROBADA' ? 'success' : ($a->estado === 'RECHAZADA' ? 'danger' : 'warning text-dark'))
              <span class="badge bg-{{ $cls }}">{{ ucfirst(strtolower($a->estado ?? 'PENDIENTE')) }}</span>
            </td>
            <td class="text-end">
              @if(($a->estado ?? 'PENDIENTE') === 'PENDIENTE')
                <form action="{{ route('aprobaciones.approve', $a) }}" method="POST" class="d-inline">
                  @csrf
                  <button class="btn btn-sm btn-success">Aprobar</button>
                </form>
                <form action="{{ route('aprobaciones.reject', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Rechazar esta asignación?');">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger">Rechazar</button>
                </form>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div>{{ $asignaciones->links() }}</div>
@endif
@endsection
