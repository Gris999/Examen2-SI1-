@php($title = 'Aulas')
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Aulas</h3>
  <a href="{{ route('aulas.create') }}" class="btn btn-primary">Nueva Aula</a>
</div>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-4">
    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por código, nombre, tipo o ubicación...">
  </div>
  <div class="col-md-3">
    <select name="tipo" class="form-select" onchange="this.form.submit()">
      <option value="">Todos los tipos</option>
      @foreach($tipos as $t)
        <option value="{{ $t }}" @selected($tipo===$t)>{{ $t }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100" type="submit">Filtrar</button>
  </div>
</form>

@if ($aulas->count() === 0)
  <div class="alert alert-info">No hay aulas registradas.</div>
@else
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Código</th>
          <th>Nombre</th>
          <th>Tipo</th>
          <th>Capacidad</th>
          <th>Ubicación</th>
          <th>Estado</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($aulas as $a)
        <tr>
          <td>{{ $a->id_aula }}</td>
          <td>{{ $a->codigo }}</td>
          <td>{{ $a->nombre }}</td>
          <td>{{ $a->tipo }}</td>
          <td>{{ $a->capacidad }}</td>
          <td>{{ $a->ubicacion }}</td>
          <td>
            @if(($usoMap[$a->id_aula] ?? 0) > 0)
              <span class="badge bg-warning text-dark">En uso</span>
            @else
              <span class="badge bg-success">Libre</span>
            @endif
          </td>
          <td class="text-end">
            <a href="{{ route('aulas.edit', $a) }}" class="btn btn-sm btn-outline-primary">Editar</a>
            <form action="{{ route('aulas.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta aula?');">
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
    {{ $aulas->links() }}
  </div>
@endif
@endsection

