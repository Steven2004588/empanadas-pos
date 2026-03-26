<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Empanadas POS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; font-size: 1.3rem; }
        .sidebar { min-height: calc(100vh - 56px); background: #212529; }
        .sidebar .nav-link { color: #adb5bd; padding: 10px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #343a40; border-radius: 6px; }
        .sidebar .nav-link i { margin-right: 8px; }
        .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; }
        .btn { border-radius: 8px; }
        .alert { border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">
        <i class="bi bi-shop"></i> Empanadas POS
    </a>
    <div class="d-flex gap-3">
        <a href="{{ route('pos.index') }}" class="btn btn-warning btn-sm">
            <i class="bi bi-cart3"></i> Punto de Venta
        </a>
        <a href="{{ route('admin.ventas.index') }}" class="btn btn-outline-light btn-sm">
    <i class="bi bi-clock-history"></i> Ventas
</a>
<a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-light btn-sm">
    <i class="bi bi-people"></i> Clientes
</a>
<a href="{{ route('admin.reportes.index') }}" class="btn btn-outline-light btn-sm">
    <i class="bi bi-bar-chart"></i> Reportes
</a>
<a href="{{ route('admin.productos.index') }}" class="btn btn-outline-light btn-sm">
    <i class="bi bi-gear"></i> Administración
</a>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        @hasSection('sidebar')
        <div class="col-md-2 sidebar py-3">
            <nav class="nav flex-column gap-1">
                <a href="{{ route('admin.productos.index') }}"
                   class="nav-link {{ request()->is('admin/productos*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Productos
                </a>
                <a href="{{ route('admin.clientes.index') }}"
                   class="nav-link {{ request()->is('admin/clientes*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Clientes
                </a>
                <a href="{{ route('admin.reportes.index') }}"
                   class="nav-link {{ request()->is('admin/reportes*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i> Reportes
                </a>
                <a href="{{ route('admin.ventas.index') }}"
   class="nav-link {{ request()->is('admin/ventas*') ? 'active' : '' }}">
    <i class="bi bi-clock-history"></i> Ventas
</a>
            </nav>
        </div>
        <div class="col-md-10 py-4 px-4">
        @else
        <div class="col-12 py-3 px-3">
        @endif

            {{-- Alertas --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>