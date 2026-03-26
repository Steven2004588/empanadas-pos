@extends('layouts.app')
@section('title', 'Reportes')
@section('sidebar') @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-bar-chart"></i> Reportes de Ventas</h4>
</div>

{{-- Filtro de fechas --}}
<form method="GET" action="{{ route('admin.reportes.index') }}" class="card mb-4">
    <div class="card-body d-flex gap-3 align-items-end flex-wrap">
        <div>
            <label class="form-label fw-semibold small">Desde</label>
            <input type="date" name="desde" class="form-control" value="{{ $desde }}">
        </div>
        <div>
            <label class="form-label fw-semibold small">Hasta</label>
            <input type="date" name="hasta" class="form-control" value="{{ $hasta }}">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-funnel"></i> Filtrar
        </button>
    </div>
</form>

{{-- Tarjetas resumen --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center border-0" style="background: linear-gradient(135deg, #667eea, #764ba2); color:white;">
            <div class="card-body">
                <i class="bi bi-cash-stack fs-2"></i>
                <h3 class="mt-2">${{ number_format($totalVentas, 0, ',', '.') }}</h3>
                <p class="mb-0 opacity-75">Total en ventas</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0" style="background: linear-gradient(135deg, #f093fb, #f5576c); color:white;">
            <div class="card-body">
                <i class="bi bi-shop fs-2"></i>
                <h3 class="mt-2">{{ $ventasMostrador }}</h3>
                <p class="mb-0 opacity-75">Ventas de mostrador</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0" style="background: linear-gradient(135deg, #4facfe, #00f2fe); color:white;">
            <div class="card-body">
                <i class="bi bi-people fs-2"></i>
                <h3 class="mt-2">{{ $ventasRegistradas }}</h3>
                <p class="mb-0 opacity-75">Ventas a clientes registrados</p>
            </div>
        </div>
    </div>
</div>

{{-- Gráficas --}}
<div class="row g-3 mb-4">

    {{-- Productos más vendidos --}}
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header fw-semibold">
                <i class="bi bi-trophy"></i> Productos más vendidos
            </div>
            <div class="card-body">
                @if($productosMasVendidos->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1"></i>
                        <p>No hay ventas en este período</p>
                    </div>
                @else
                    <canvas id="graficoProductos" height="200"></canvas>
                @endif
            </div>
        </div>
    </div>

    {{-- Mostrador vs Registrados --}}
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header fw-semibold">
                <i class="bi bi-pie-chart"></i> Tipo de venta
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                @if($ventasMostrador + $ventasRegistradas === 0)
                    <div class="text-center text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p>No hay ventas en este período</p>
                    </div>
                @else
                    <canvas id="graficoPie" height="200"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Clientes por ciudad --}}
@if($clientesPorCiudad->isNotEmpty())
<div class="card">
    <div class="card-header fw-semibold">
        <i class="bi bi-geo-alt"></i> Clientes por ciudad
    </div>
    <div class="card-body">
        <canvas id="graficoCiudades" height="100"></canvas>
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const colores = [
        '#667eea','#f5576c','#4facfe','#43e97b','#fa709a',
        '#fee140','#a18cd1','#fda085','#84fab0','#8fd3f4'
    ];

    @if($productosMasVendidos->isNotEmpty())
    new Chart(document.getElementById('graficoProductos'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($productosMasVendidos->map(fn($p) => $p->producto?->nombre ?? 'Eliminado')->toArray()) !!},
            datasets: [{
                label: 'Unidades vendidas',
                data: {!! json_encode($productosMasVendidos->pluck('total_vendido')->toArray()) !!},
                backgroundColor: colores.slice(0, {{ $productosMasVendidos->count() }}),
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
    @endif

    @if($ventasMostrador + $ventasRegistradas > 0)
    new Chart(document.getElementById('graficoPie'), {
        type: 'doughnut',
        data: {
            labels: ['Mostrador', 'Clientes registrados'],
            datasets: [{
                data: [{{ $ventasMostrador }}, {{ $ventasRegistradas }}],
                backgroundColor: ['#f5576c', '#4facfe'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    @endif

    @if($clientesPorCiudad->isNotEmpty())
    new Chart(document.getElementById('graficoCiudades'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($clientesPorCiudad->pluck('ciudad')->toArray()) !!},
            datasets: [{
                label: 'Clientes',
                data: {!! json_encode($clientesPorCiudad->pluck('total')->toArray()) !!},
                backgroundColor: colores.slice(0, {{ $clientesPorCiudad->count() }}),
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
    @endif
</script>
@endpush
@endsection