<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        $productos = Producto::activos()->orderBy('nombre')->get();
        return view('pos.index', compact('productos'));
    }

    public function buscarCliente(Request $request)
    {
        $cliente = Cliente::where('numero_documento', $request->documento)
                          ->where('es_mostrador', false)
                          ->first();

        if ($cliente) {
            return response()->json(['found' => true, 'cliente' => $cliente]);
        }

        return response()->json(['found' => false]);
    }

    public function crearCliente(Request $request)
    {
        $request->validate([
            'tipo_documento'   => 'required|in:cedula,tarjeta_identidad,pasaporte,nit',
            'numero_documento' => 'required|string|max:20|unique:clientes',
            'nombre_completo'  => 'required|string|max:150',
            'ciudad'           => 'nullable|string|max:100',
            'telefono'         => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::create($request->all());

        return response()->json(['success' => true, 'cliente' => $cliente]);
    }

    public function registrarVenta(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'items'      => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad'    => 'required|integer|min:1',
        ]);

        $venta = Venta::create([
            'cliente_id' => $request->cliente_id,
            'fecha'      => now(),
            'total'      => 0,
        ]);

        foreach ($request->items as $item) {
            $producto = Producto::find($item['producto_id']);

            DetalleVenta::create([
                'venta_id'       => $venta->id,
                'producto_id'    => $producto->id,
                'cantidad'       => $item['cantidad'],
                'precio_unitario'=> $producto->precio,
                'subtotal'       => $producto->precio * $item['cantidad'],
            ]);
        }

        $venta->recalcularTotal();

        return response()->json([
            'success'  => true,
            'mensaje'  => '¡Venta registrada correctamente!',
            'venta_id' => $venta->id,
            'total'    => $venta->total,
        ]);
    }
}