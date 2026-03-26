@extends('layouts.app')
@section('title', 'Historial de Ventas')
@section('sidebar') @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Ventas</h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Detalle</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                <tr>
                    <td class="ps-3 text-muted small">{{ $venta->id }}</td>
                    <td>{{ $venta->fecha->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($venta->cliente->es_mostrador)
                            <span class="badge bg-secondary">Mostrador</span>
                        @else
                            <i class="bi bi-person-fill text-primary"></i>
                            {{ $venta->cliente->nombre_completo }}
                        @endif
                    </td>
                    <td class="text-end fw-bold text-success">
                        ${{ number_format($venta->total, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.ventas.show', $venta) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        No hay ventas registradas aún
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($ventas->hasPages())
<div class="mt-3 d-flex justify-content-center">
    {{ $ventas->links() }}
</div>
@endif

@endsection