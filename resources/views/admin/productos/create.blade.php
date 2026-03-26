@extends('layouts.app')
@section('title', 'Nuevo Producto')
@section('sidebar') @endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Nuevo Producto</h4>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body p-4">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.productos.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre') }}"
                       placeholder="Ej: Empanada de pipián">
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"
                          placeholder="Descripción opcional">{{ old('descripcion') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Precio <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" name="precio" step="0.01" min="0"
                           class="form-control @error('precio') is-invalid @enderror"
                           value="{{ old('precio') }}"
                           placeholder="0.00">
                </div>
                @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="activo"
                           id="activo" value="1"
                           {{ old('activo', '1') ? 'checked' : '' }}>
                    {{-- ✅ value="1" y checked por defecto con old() --}}
                    <label class="form-check-label fw-semibold" for="activo">Producto activo</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar
                </button>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection