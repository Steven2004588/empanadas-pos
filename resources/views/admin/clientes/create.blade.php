@extends('layouts.app')
@section('title', 'Nuevo Cliente')
@section('sidebar') @endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0"><i class="bi bi-person-plus"></i> Nuevo Cliente</h4>
</div>

<div class="card" style="max-width: 650px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.clientes.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipo de Documento <span class="text-danger">*</span></label>
                    <select name="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror">
                        <option value="cedula"           {{ old('tipo_documento') == 'cedula'           ? 'selected' : '' }}>Cédula</option>
                        <option value="tarjeta_identidad"{{ old('tipo_documento') == 'tarjeta_identidad'? 'selected' : '' }}>Tarjeta de Identidad</option>
                        <option value="pasaporte"        {{ old('tipo_documento') == 'pasaporte'        ? 'selected' : '' }}>Pasaporte</option>
                        <option value="nit"              {{ old('tipo_documento') == 'nit'              ? 'selected' : '' }}>NIT</option>
                    </select>
                    @error('tipo_documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Número de Documento <span class="text-danger">*</span></label>
                    <input type="text" name="numero_documento"
                           class="form-control @error('numero_documento') is-invalid @enderror"
                           value="{{ old('numero_documento') }}" placeholder="Ej: 1234567890">
                    @error('numero_documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Nombre Completo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_completo"
                           class="form-control @error('nombre_completo') is-invalid @enderror"
                           value="{{ old('nombre_completo') }}" placeholder="Ej: Juan Pérez">
                    @error('nombre_completo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                           value="{{ old('direccion') }}" placeholder="Ej: Calle 10 # 5-20">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ciudad</label>
                    <input type="text" name="ciudad" class="form-control"
                           value="{{ old('ciudad') }}" placeholder="Ej: Bucaramanga">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono') }}" placeholder="Ej: 3001234567">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar
                </button>
                <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection