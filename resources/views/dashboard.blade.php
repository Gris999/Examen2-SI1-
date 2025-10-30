@php($title = 'Dashboard')
@extends('layouts.app')

@section('content')
<div class="row g-3">
  <div class="col-12">
    <div class="alert alert-success">Bienvenido, {{ auth()->user()->nombre ?? auth()->user()->correo }}.</div>
  </div>
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">CU1 Autenticación</h5>
        <p class="card-text">Login, Logout, Recuperación</p>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">Ir</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">CU2 Docentes</h5>
        <a href="#" class="btn btn-outline-secondary btn-sm">Próximamente</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">CU3 Materias</h5>
        <a href="#" class="btn btn-outline-secondary btn-sm">Próximamente</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">CU4 Grupos</h5>
        <a href="#" class="btn btn-outline-secondary btn-sm">Próximamente</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">CU5 Aulas</h5>
        <a href="#" class="btn btn-outline-secondary btn-sm">Próximamente</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">CU6 Carga Horaria</h5>
        <a href="#" class="btn btn-outline-secondary btn-sm">Próximamente</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">CU7 Aprobación Asignaciones</h5>
        <a href="#" class="btn btn-outline-secondary btn-sm">Próximamente</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">CU8 Horarios</h5>
        <a href="#" class="btn btn-outline-secondary btn-sm">Próximamente</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title">CU10 Asistencia Docente</h5>
        <a href="#" class="btn btn-outline-secondary btn-sm">Próximamente</a>
      </div>
    </div>
  </div>
</div>
@endsection
