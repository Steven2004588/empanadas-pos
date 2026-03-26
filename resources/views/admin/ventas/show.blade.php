@extends('layouts.app')
@section('title', 'Detalle de Venta #' . $venta->id)
@section('sidebar') @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-receipt"></i> Venta #{{ $venta->id }}</h4>
    <a href="{{ route('admin.ventas.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row g-3">

    {{-- Info de la venta --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-dark text-white fw-semibold">
                <i class="bi bi-info-circle"></i> Información
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <span class="text-muted small">Fecha</span><br>
                    <strong>{{ $venta->fecha->format('d/m/Y H:i') }}</strong>
                </p>
                <p class="mb-2">
                    <span class="text-muted small">Cliente</span><br>
                    @if($venta->cliente->es_mostrador)
                        <span class="badge bg-secondary fs-6">Venta de mostrador</span>
                    @else
                        <strong>{{ $venta->cliente->nombre_completo }}</strong><br>
                        <span class="text-muted small">
                            {{ strtoupper($venta->cliente->tipo_documento) }}:
                            {{ $venta->cliente->numero_documento }}
                        </span>
                        @if($venta->cliente->telefono)
                            <br><span class="text-muted small">
                                <i class="bi bi-telephone"></i> {{ $venta->cliente->telefono }}
                            </span>
                        @endif
                        @if($venta->cliente->ciudad)
                            <br><span class="text-muted small">
                                <i class="bi bi-geo-alt"></i> {{ $venta->cliente->ciudad }}
                            </span>
                        @endif
                    @endif
                </p>
                <hr>
                <p class="mb-0">
                    <span class="text-muted small">Total de la venta</span><br>
                    <span class="fs-3 fw-bold text-success">
                        ${{ number_format($venta->total, 0, ',', '.') }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- Productos vendidos --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header bg-dark text-white fw-semibold">
                <i class="bi bi-bag"></i> Productos
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio unit.</th>
                            <th class="text-end pe-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $detalle)
                        <tr>
                            <td class="ps-3">
                                {{ $detalle->producto?->nombre ?? 'Producto eliminado' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill">
                                    {{ $detalle->cantidad }}
                                </span>
                            </td>
                            <td class="text-end text-muted">
                                ${{ number_format($detalle->precio_unitario, 0, ',', '.') }}
                            </td>
                            <td class="text-end pe-3 fw-bold">
                                ${{ number_format($detalle->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold ps-3">TOTAL</td>
                            <td class="text-end pe-3 fw-bold text-success fs-5">
                                ${{ number_format($venta->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection 