<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\VentaController;

// Ruta raíz redirige al POS
Route::get('/', fn() => redirect()->route('pos.index'));

// =============================================
// PUNTO DE VENTA
// =============================================
Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/',                      [PosController::class, 'index'])->name('index');
    Route::post('/buscar-cliente',       [PosController::class, 'buscarCliente'])->name('buscar-cliente');
    Route::post('/crear-cliente',        [PosController::class, 'crearCliente'])->name('crear-cliente');
    Route::post('/registrar-venta',      [PosController::class, 'registrarVenta'])->name('registrar-venta');
});

// =============================================
// PANEL DE ADMINISTRACIÓN
// =============================================
Route::prefix('admin')->name('admin.')->group(function () {

    // Ruta raíz del admin redirige a productos
    Route::get('/', fn() => redirect()->route('admin.productos.index'));

    // Productos
    Route::resource('productos', ProductoController::class)->except(['show']);

    // Clientes
    Route::resource('clientes', ClienteController::class)->except(['show']);

    // Reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('ventas', [VentaController::class, 'index'])->name('ventas.index');
Route::get('ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');
});