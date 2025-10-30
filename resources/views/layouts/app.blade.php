<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sistema Académico' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('dashboard') }}">Académico</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample" aria-controls="navbarsExample" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExample">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @auth
        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('docentes.index') }}">Docentes</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('materias.index') }}">Materias</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('grupos.index') }}">Grupos</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('aulas.index') }}">Aulas</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('carga.index') }}">Carga Horaria</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('aprobaciones.index') }}">Aprobaciones</a></li>
        @endauth
      </ul>
      <ul class="navbar-nav ms-auto">
        @auth
          <li class="nav-item"><span class="navbar-text me-2">{{ auth()->user()->nombre ?? auth()->user()->correo }}</span></li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button class="btn btn-outline-light btn-sm">Salir</button>
            </form>
          </li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
        @endauth
      </ul>
    </div>
  </div>
  </nav>

<main class="container">
  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if (session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{ $slot ?? '' }}
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
