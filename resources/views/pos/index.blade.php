@extends('layouts.app')
@section('title', 'Punto de Venta')

@section('content')
<div x-data="pos()" class="h-full">

    {{-- Mensaje de éxito --}}
    <div x-show="ventaExitosa"
     x-transition
     class="alert alert-success alert-dismissible mb-3"
     style="display:none">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <i class="bi bi-check-circle-fill"></i>
            <strong>¡Venta registrada!</strong>
            Total: $<span x-text="formatear(ultimoTotal)"></span>
        </div>
        <a :href="'/admin/ventas/' + ultimaVentaId" class="btn btn-sm btn-success border-white">
            <i class="bi bi-eye"></i> Ver venta
        </a>
    </div>
    <button type="button" class="btn-close" @click="ventaExitosa = false"></button>
</div>

    <div class="row g-3" style="height: calc(100vh - 120px);">

        {{-- COLUMNA IZQUIERDA: Productos --}}
        <div class="col-md-7 h-100">
            <div class="card h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-grid"></i> Productos</span>
                    <span class="badge bg-secondary">{{ $productos->count() }} disponibles</span>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row g-2">
                        @forelse($productos as $producto)
                        <div class="col-6 col-md-4">
                            <div class="card h-100 border-2 producto-card"
                                 style="cursor:pointer; transition: all 0.15s;"
                                 @click="agregarProducto({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->precio }})"
                                 @mouseenter="$event.currentTarget.style.borderColor='#0d6efd'; $event.currentTarget.style.transform='scale(1.03)'"
                                 @mouseleave="$event.currentTarget.style.borderColor=''; $event.currentTarget.style.transform='scale(1)'">
                                <div class="card-body text-center p-2">
                                    <div class="fs-2 mb-1">🫓</div>
                                    <div class="fw-bold small">{{ $producto->nombre }}</div>
                                    <div class="text-success fw-bold">${{ number_format($producto->precio, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted py-5">
                            <i class="bi bi-box-seam fs-1"></i>
                            <p>No hay productos activos</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: Carrito --}}
        <div class="col-md-5 h-100 d-flex flex-column gap-2">

            {{-- Cliente --}}
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-person"></i> Cliente
                </div>
                <div class="card-body p-2">

                    {{-- Mostrador por defecto --}}
                    <div x-show="!clienteSeleccionado" class="d-flex gap-2 align-items-center">
                        <span class="badge bg-secondary fs-6 flex-grow-1 text-start p-2">
                            <i class="bi bi-shop"></i> Venta de mostrador
                        </span>
                        <button class="btn btn-sm btn-outline-primary" @click="buscarCliente = true">
                            <i class="bi bi-search"></i> Cambiar
                        </button>
                    </div>

                    {{-- Cliente seleccionado --}}
                    <div x-show="clienteSeleccionado" class="d-flex gap-2 align-items-center">
                        <span class="badge bg-primary fs-6 flex-grow-1 text-start p-2">
                            <i class="bi bi-person-check"></i>
                            <span x-text="clienteNombre"></span>
                        </span>
                        <button class="btn btn-sm btn-outline-secondary" @click="quitarCliente()">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>

                    {{-- Panel buscar cliente --}}
                    <div x-show="buscarCliente" x-transition class="mt-2" style="display:none">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" placeholder="Número de documento..."
                                   x-model="documentoBuscar"
                                   @keyup.enter="buscar()"
                                   x-ref="inputDoc">
                            <button class="btn btn-primary" @click="buscar()">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>

                        {{-- Resultado búsqueda --}}
                        <div x-show="clienteEncontrado" class="alert alert-success py-1 px-2 small mb-2">
                            <i class="bi bi-person-check"></i>
                            <span x-text="clienteNombre"></span>
                            <button class="btn btn-success btn-sm float-end" @click="confirmarCliente()">Seleccionar</button>
                        </div>
                        <div x-show="clienteNoEncontrado" class="alert alert-warning py-1 px-2 small mb-2">
                            <i class="bi bi-person-x"></i> No encontrado.
                            <button class="btn btn-warning btn-sm float-end" @click="crearNuevo = true; clienteNoEncontrado = false">
                                Crear nuevo
                            </button>
                        </div>

                        {{-- Formulario crear cliente rápido --}}
                        <div x-show="crearNuevo" class="border rounded p-2 bg-light small">
                            <p class="fw-bold mb-1">Nuevo cliente</p>
                            <select x-model="nuevoCliente.tipo_documento" class="form-select form-select-sm mb-1">
                                <option value="cedula">Cédula</option>
                                <option value="tarjeta_identidad">Tarjeta Identidad</option>
                                <option value="pasaporte">Pasaporte</option>
                                <option value="nit">NIT</option>
                            </select>
                            <input type="text" class="form-control form-control-sm mb-1"
                                   placeholder="Número documento" x-model="nuevoCliente.numero_documento">
                            <input type="text" class="form-control form-control-sm mb-1"
                                   placeholder="Nombre completo *" x-model="nuevoCliente.nombre_completo">
                            <input type="text" class="form-control form-control-sm mb-1"
                                   placeholder="Ciudad (opcional)" x-model="nuevoCliente.ciudad">
                            <input type="text" class="form-control form-control-sm mb-2"
                                   placeholder="Teléfono (opcional)" x-model="nuevoCliente.telefono">
                            <div class="d-flex gap-1">
                                <button class="btn btn-success btn-sm flex-grow-1" @click="guardarNuevoCliente()">
                                    <i class="bi bi-save"></i> Guardar
                                </button>
                                <button class="btn btn-secondary btn-sm" @click="crearNuevo = false">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Carrito --}}
            <div class="card flex-grow-1 overflow-hidden d-flex flex-column">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <span><i class="bi bi-cart3"></i> Orden</span>
                    <button class="btn btn-sm btn-outline-danger" @click="limpiarCarrito()"
                            x-show="carrito.length > 0">
                        <i class="bi bi-trash"></i> Limpiar
                    </button>
                </div>

                {{-- Items del carrito --}}
                <div class="card-body overflow-auto p-2 flex-grow-1">
                    <template x-if="carrito.length === 0">
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-cart-x fs-1"></i>
                            <p class="small">Selecciona productos</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in carrito" :key="item.id">
                        <div class="d-flex align-items-center gap-2 mb-2 p-2 border rounded">
                            <div class="flex-grow-1">
                                <div class="fw-bold small" x-text="item.nombre"></div>
                                <div class="text-success small">$<span x-text="formatear(item.precio)"></span></div>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <button class="btn btn-sm btn-outline-secondary"
                                        style="width:28px;height:28px;padding:0"
                                        @click="disminuir(index)">−</button>
                                <span class="fw-bold mx-1" x-text="item.cantidad"></span>
                                <button class="btn btn-sm btn-outline-secondary"
                                        style="width:28px;height:28px;padding:0"
                                        @click="aumentar(index)">+</button>
                                <button class="btn btn-sm btn-outline-danger ms-1"
                                        style="width:28px;height:28px;padding:0"
                                        @click="eliminar(index)">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="text-end" style="min-width:60px">
                                <small class="fw-bold">$<span x-text="formatear(item.precio * item.cantidad)"></span></small>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Total y botón --}}
                <div class="card-footer p-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold fs-5">TOTAL</span>
                        <span class="fw-bold fs-4 text-success">$<span x-text="formatear(total)"></span></span>
                    </div>
                    <button class="btn btn-success w-100 btn-lg"
                            @click="registrarVenta()"
                            :disabled="carrito.length === 0 || procesando">
                        <span x-show="!procesando"><i class="bi bi-check-circle"></i> Registrar Venta</span>
                        <span x-show="procesando"><i class="bi bi-hourglass-split"></i> Procesando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function pos() {
    return {
        // Estado del carrito
        carrito: [],
        procesando: false,
        ventaExitosa: false,
        ultimoTotal: 0,

        // Estado del cliente
        clienteSeleccionado: false,
        clienteId: {{ \App\Models\Cliente::whereRaw('"es_mostrador" = true')->value('id') ?? 'null' }},
        clienteIdMostrador: {{ \App\Models\Cliente::whereRaw('"es_mostrador" = true')->value('id') ?? 'null' }},
        clienteNombre: '',
        buscarCliente: false,
        documentoBuscar: '',
        clienteEncontrado: false,
        clienteNoEncontrado: false,
        crearNuevo: false,
        nuevoCliente: {
            tipo_documento: 'cedula',
            numero_documento: '',
            nombre_completo: '',
            ciudad: '',
            telefono: '',
        },

        // Computed total
        get total() {
            return this.carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        },

        // Formatear número colombiano
        formatear(num) {
            return Math.round(num).toLocaleString('es-CO');
        },

        // Agregar producto al carrito
        agregarProducto(id, nombre, precio) {
            const existe = this.carrito.find(i => i.id === id);
            if (existe) {
                existe.cantidad++;
            } else {
                this.carrito.push({ id, nombre, precio, cantidad: 1 });
            }
        },

        aumentar(index) { this.carrito[index].cantidad++; },

        disminuir(index) {
            if (this.carrito[index].cantidad > 1) {
                this.carrito[index].cantidad--;
            } else {
                this.eliminar(index);
            }
        },

        eliminar(index) { this.carrito.splice(index, 1); },

        limpiarCarrito() { this.carrito = []; },

        // Cliente
        quitarCliente() {
            this.clienteSeleccionado = false;
            this.clienteId = this.clienteIdMostrador;
            this.clienteNombre = '';
            this.buscarCliente = false;
            this.documentoBuscar = '';
            this.clienteEncontrado = false;
            this.clienteNoEncontrado = false;
            this.crearNuevo = false;
        },

        async buscar() {
    if (!this.documentoBuscar) return;
    this.clienteEncontrado = false;
    this.clienteNoEncontrado = false;

    try {
        const res = await fetch('{{ route("pos.buscar-cliente") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ documento: this.documentoBuscar })
        });

        if (!res.ok) {
            this.clienteNoEncontrado = true;
            this.nuevoCliente.numero_documento = this.documentoBuscar;
            return;
        }

        const data = await res.json();

        if (data.found) {
            this.clienteEncontrado = true;
            this.clienteId = data.cliente.id;
            this.clienteNombre = data.cliente.nombre_completo;
        } else {
            this.clienteNoEncontrado = true;
            this.nuevoCliente.numero_documento = this.documentoBuscar;
        }
    } catch (e) {
        this.clienteNoEncontrado = true;
        this.nuevoCliente.numero_documento = this.documentoBuscar;
    }
},

        confirmarCliente() {
            this.clienteSeleccionado = true;
            this.buscarCliente = false;
            this.clienteEncontrado = false;
            this.documentoBuscar = '';
        },

        async guardarNuevoCliente() {
            if (!this.nuevoCliente.nombre_completo) {
                alert('El nombre es obligatorio');
                return;
            }
            const res = await fetch('{{ route("pos.crear-cliente") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.nuevoCliente)
            });
            const data = await res.json();
            if (data.success) {
                this.clienteId = data.cliente.id;
                this.clienteNombre = data.cliente.nombre_completo;
                this.clienteSeleccionado = true;
                this.buscarCliente = false;
                this.crearNuevo = false;
            }
        },

        // Registrar venta
        async registrarVenta() {
            if (this.carrito.length === 0) return;
            this.procesando = true;

            const items = this.carrito.map(i => ({
                producto_id: i.id,
                cantidad: i.cantidad
            }));

            const res = await fetch('{{ route("pos.registrar-venta") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    cliente_id: this.clienteId,
                    items: items
                })
            });
            const data = await res.json();

            if (data.success) {
    this.ultimoTotal = data.total;
    this.ultimaVentaId = data.venta_id;
    this.ventaExitosa = true;
    this.limpiarCarrito();
    this.quitarCliente();
    setTimeout(() => this.ventaExitosa = false, 8000);
}
            this.procesando = false;
        }
    }
}
</script>
@endpush
@endsection