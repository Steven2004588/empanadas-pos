<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('cliente')
            ->orderByDesc('fecha')
            ->paginate(20);

        return view('admin.ventas.index', compact('ventas'));
    }

    public function show(Venta $venta)
    {
        $venta->load('cliente', 'detalles.producto');
        return view('admin.ventas.show', compact('venta'));
    }
}