<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sistema Académico' }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<style>
  .toast-container { z-index: 1080; }
  .sidebar { width: 260px; min-height: 100vh; position: fixed; top:0; left:0; background: #0b2239; display:flex; flex-direction:column; overflow-y:auto; z-index:1050; }
  .sidebar .user { padding: 16px; border-bottom: 1px solid rgba(255,255,255,.08); }
  .sidebar .user .circle { width:44px;height:44px;border-radius:50%;background:#0f766e;color:#fff;display:flex;align-items:center;justify-content:center; font-weight:600; font-size:18px; }
  .sidebar a.nav-link { color: #d7e1ea; border-radius: 10px; padding: 11px 12px; font-size: 0.95rem; }
  .sidebar a.nav-link.active, .sidebar a.nav-link:hover { background:#113252; color:#fff; }
  .content-wrap { margin-left: 0; position: relative; overflow-x:auto; }
  @media(min-width: 992px){ .content-wrap { margin-left: 260px; } }
  .topbar { position: sticky; top:0; z-index:1060; background:#fff; border-bottom:1px solid #edf2f7; padding:.75rem 1rem; }
  .crumb { color:#6c757d; }
  .btn-teal { background:#0f766e; color:#fff; border-color:#0f766e; }
  .btn-teal:hover { background:#0c5f59; border-color:#0c5f59; color:#fff; }
</style>

<div class="toast-container position-fixed top-0 end-0 p-3">
  @if (session('status'))
    <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
      <div class="d-flex">
        <div class="toast-body">{{ session('status') }}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  @endif
  @if (session('warning'))
    <div class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4500">
      <div class="d-flex">
        <div class="toast-body">{{ session('warning') }}</div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  @endif
  @if ($errors->any())
    <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
      <div class="d-flex">
        <div class="toast-body">
          <strong>Ocurrieron errores:</strong>
          <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  @endif
</div>
@auth
  <!-- Mobile topbar -->
  <div class="d-lg-none topbar d-flex justify-content-between align-items-center">
    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
      <i class="bi bi-list"></i>
    </button>
    <div class="fw-semibold">Académico</div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
    </form>
  </div>

  <aside class="sidebar d-none d-lg-flex flex-column text-white">
    <div class="user d-flex align-items-center gap-2">
      <div class="circle">{{ strtoupper(substr(auth()->user()->nombre ?? auth()->user()->correo,0,1)) }}</div>
      <div>
        <div class="fw-semibold">{{ auth()->user()->nombre ?? auth()->user()->correo }}</div>
        <small class="text-muted">{{ optional(auth()->user()->roles()->first())->nombre ?? 'Usuario' }}</small>
      </div>
    </div>
    <div class="px-2 pt-2">
      <form method="POST" action="{{ route('logout') }}" class="d-grid">
        @csrf
        <button class="btn btn-danger btn-sm w-100"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
      </form>
    </div>
    @php
      $roles = auth()->user()->roles()->pluck('nombre')->map(fn($n)=>mb_strtolower($n))->toArray();
      $isAdmin = in_array('administrador', $roles);
      $isDecano = in_array('decano', $roles);
      $isDirector = in_array('director de carrera', $roles) || in_array('director', $roles) || in_array('coordinador', $roles);
      // Fallback: si el usuario no tiene roles reconocidos, mostramos todo
      $noRoles = empty($roles) || (! $isAdmin && ! $isDecano && ! $isDirector);
    @endphp
    <nav class="p-2">
      <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-house me-2"></i>Inicio</a>

      @if($isAdmin || $isDecano || $noRoles)
        <a class="nav-link {{ request()->routeIs('docentes.*') ? 'active' : '' }}" href="{{ route('docentes.index') }}"><i class="bi bi-people me-2"></i>Docentes</a>
      @endif

      @if($isAdmin || $isDirector || $isDecano || $noRoles)
        <a class="nav-link {{ request()->routeIs('materias.*') ? 'active' : '' }}" href="{{ route('materias.index') }}"><i class="bi bi-journal-text me-2"></i>Materias</a>
      @endif

      @if($isAdmin || $isDirector || $noRoles)
        <a class="nav-link {{ request()->routeIs('grupos.*') ? 'active' : '' }}" href="{{ route('grupos.index') }}"><i class="bi bi-collection me-2"></i>Grupos</a>
      @endif

      @if($isAdmin || $noRoles)
        <a class="nav-link {{ request()->routeIs('aulas.*') ? 'active' : '' }}" href="{{ route('aulas.index') }}"><i class="bi bi-building me-2"></i>Aulas</a>
      @endif

      @if($isAdmin || $isDirector || $noRoles)
        <a class="nav-link {{ request()->routeIs('carga.*') ? 'active' : '' }}" href="{{ route('carga.index') }}"><i class="bi bi-calendar-week me-2"></i>Carga Horaria</a>
      @endif

      @if($isAdmin || $isDecano || $noRoles)
        <a class="nav-link {{ request()->routeIs('aprobaciones.*') ? 'active' : '' }}" href="{{ route('aprobaciones.index') }}"><i class="bi bi-check2-circle me-2"></i>Aprobaciones</a>
      @endif

      @if($isAdmin || $isDirector || $noRoles)
        <a class="nav-link {{ request()->routeIs('horarios.*') ? 'active' : '' }}" href="{{ route('horarios.index') }}"><i class="bi bi-calendar-event me-2"></i>Horarios</a>
      @endif

      @if($isAdmin || $isDecano || $noRoles)
        <a class="nav-link {{ request()->routeIs('asistencias.*') ? 'active' : '' }}" href="{{ route('asistencias.index') }}"><i class="bi bi-clipboard-check me-2"></i>Asistencia</a>
      @endif
    </nav>
  </aside>

  <!-- Offcanvas mobile menu -->
  <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="mobileMenuLabel">Menú</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      @php
        $roles = auth()->user()->roles()->pluck('nombre')->map(fn($n)=>mb_strtolower($n))->toArray();
        $isAdmin = in_array('administrador', $roles);
        $isDecano = in_array('decano', $roles);
        $isDirector = in_array('director de carrera', $roles) || in_array('director', $roles) || in_array('coordinador', $roles);
        $noRoles = empty($roles) || (! $isAdmin && ! $isDecano && ! $isDirector);
      @endphp
      <div class="list-group list-group-flush flex-grow-1">
        <a class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-house me-2"></i>Inicio</a>
        @if($isAdmin || $isDecano || $noRoles)
          <a class="list-group-item list-group-item-action {{ request()->routeIs('docentes.*') ? 'active' : '' }}" href="{{ route('docentes.index') }}"><i class="bi bi-people me-2"></i>Docentes</a>
        @endif
        @if($isAdmin || $isDirector || $isDecano || $noRoles)
          <a class="list-group-item list-group-item-action {{ request()->routeIs('materias.*') ? 'active' : '' }}" href="{{ route('materias.index') }}"><i class="bi bi-journal-text me-2"></i>Materias</a>
        @endif
        @if($isAdmin || $isDirector || $noRoles)
          <a class="list-group-item list-group-item-action {{ request()->routeIs('grupos.*') ? 'active' : '' }}" href="{{ route('grupos.index') }}"><i class="bi bi-collection me-2"></i>Grupos</a>
        @endif
        @if($isAdmin || $noRoles)
          <a class="list-group-item list-group-item-action {{ request()->routeIs('aulas.*') ? 'active' : '' }}" href="{{ route('aulas.index') }}"><i class="bi bi-building me-2"></i>Aulas</a>
        @endif
        @if($isAdmin || $isDirector || $noRoles)
          <a class="list-group-item list-group-item-action {{ request()->routeIs('carga.*') ? 'active' : '' }}" href="{{ route('carga.index') }}"><i class="bi bi-calendar-week me-2"></i>Carga Horaria</a>
        @endif
        @if($isAdmin || $isDecano || $noRoles)
          <a class="list-group-item list-group-item-action {{ request()->routeIs('aprobaciones.*') ? 'active' : '' }}" href="{{ route('aprobaciones.index') }}"><i class="bi bi-check2-circle me-2"></i>Aprobaciones</a>
        @endif
        @if($isAdmin || $isDirector || $noRoles)
          <a class="list-group-item list-group-item-action {{ request()->routeIs('horarios.*') ? 'active' : '' }}" href="{{ route('horarios.index') }}"><i class="bi bi-calendar-event me-2"></i>Horarios</a>
        @endif
        @if($isAdmin || $isDecano || $noRoles)
          <a class="list-group-item list-group-item-action {{ request()->routeIs('asistencias.*') ? 'active' : '' }}" href="{{ route('asistencias.index') }}"><i class="bi bi-clipboard-check me-2"></i>Asistencia</a>
        @endif
      </div>
      <form method="POST" action="{{ route('logout') }}" class="pt-3">
        @csrf
        <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
      </form>
    </div>
  </div>
@endauth

@auth
  <div class="topbar d-none d-lg-block content-wrap">
    <div class="d-flex justify-content-between align-items-center">
      <div class="crumb">
        <i class="bi bi-chevron-right"></i> {{ $title ?? (ucfirst(str_replace('.', ' ', request()->route()->getName())) ) }}
      </div>
      <div class="d-flex gap-2">
        @php $r = request()->route()->getName(); @endphp
        @if(str_starts_with($r,'docentes.'))
          <a href="{{ route('docentes.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Docente</a>
        @elseif(str_starts_with($r,'materias.'))
          <a href="{{ route('materias.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Materia</a>
        @elseif(str_starts_with($r,'grupos.'))
          <a href="{{ route('grupos.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Grupo</a>
        @elseif(str_starts_with($r,'aulas.'))
          <a href="{{ route('aulas.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Aula</a>
        @elseif(str_starts_with($r,'carga.'))
          <a href="{{ route('carga.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Asignación</a>
        @elseif(str_starts_with($r,'horarios.'))
          <a href="{{ route('horarios.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Horario</a>
        @elseif(str_starts_with($r,'asistencias.'))
          <a href="{{ route('asistencias.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Registrar Asistencia</a>
        @endif
      </div>
    </div>
  </div>
@endauth

<main class="content-wrap container-fluid pt-3">

  {{ $slot ?? '' }}
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  (function(){
    var els = document.querySelectorAll('.toast');
    els.forEach(function(el){ new bootstrap.Toast(el).show(); });
  })();
</script>
</body>
</html>
