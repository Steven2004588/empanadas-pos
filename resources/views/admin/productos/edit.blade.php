@extends('layouts.app')
@section('title', 'Editar Producto')
@section('sidebar') @endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0"><i class="bi bi-pencil"></i> Editar Producto</h4>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.productos.update', $producto) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre', $producto->nombre) }}">
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Precio <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" name="precio" step="0.01" min="0"
                           class="form-control @error('precio') is-invalid @enderror"
                           value="{{ old('precio', $producto->precio) }}">
                </div>
                @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="activo"
                           id="activo" {{ $producto->activo ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="activo">Producto activo</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar
                </button>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection