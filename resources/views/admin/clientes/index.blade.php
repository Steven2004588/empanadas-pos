@extends('layouts.app')
@section('title', 'Clientes')
@section('sidebar') @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people"></i> Clientes</h4>
    <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Cliente
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Ciudad</th>
                    <th>Teléfono</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ strtoupper(str_replace('_', ' ', $cliente->tipo_documento)) }}</span>
                        {{ $cliente->numero_documento }}
                    </td>
                    <td>{{ $cliente->nombre_completo }}</td>
                    <td>{{ $cliente->ciudad ?? '—' }}</td>
                    <td>{{ $cliente->telefono ?? '—' }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.clientes.edit', $cliente) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.clientes.destroy', $cliente) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar este cliente?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No hay clientes registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection