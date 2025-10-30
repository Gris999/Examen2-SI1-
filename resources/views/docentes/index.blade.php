@php($title = 'Docentes')
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Docentes</h3>
  <a href="{{ route('docentes.create') }}" class="btn btn-primary">Nuevo Docente</a>
  </div>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-6">
    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre, correo, código, profesión...">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100" type="submit">Buscar</button>
  </div>
</form>

@if ($docentes->count() === 0)
  <div class="alert alert-info">No hay docentes registrados.</div>
@else
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Código</th>
          <th>Profesión</th>
          <th>Grado Académico</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($docentes as $d)
        <tr>
          <td>{{ $d->id_docente }}</td>
          <td>{{ $d->usuario->nombre ?? '' }} {{ $d->usuario->apellido ?? '' }}</td>
          <td>{{ $d->usuario->correo ?? '' }}</td>
          <td>{{ $d->codigo_docente }}</td>
          <td>{{ $d->profesion }}</td>
          <td>{{ $d->grado_academico }}</td>
          <td class="text-end">
            <a href="{{ route('docentes.edit', $d) }}" class="btn btn-sm btn-outline-primary">Editar</a>
            <form action="{{ route('docentes.destroy', $d) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este docente?');">
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
    {{ $docentes->links() }}
  </div>
@endif
@endsection

