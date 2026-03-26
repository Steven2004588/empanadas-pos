<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->toDateString();
        $hasta = $request->hasta ?? now()->toDateString();

        // Total de ventas en el rango
        $totalVentas = Venta::whereBetween('fecha', [$desde, $hasta.' 23:59:59'])->sum('total');

        // Productos más vendidos
        $productosMasVendidos = DetalleVenta::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->with('producto')
            ->whereHas('venta', fn($q) => $q->whereBetween('fecha', [$desde, $hasta.' 23:59:59']))
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // ✅ Corregido para PostgreSQL
        $ventasMostrador = Venta::whereHas('cliente', fn($q) => $q->whereRaw('"es_mostrador" = true'))
                                  ->whereBetween('fecha', [$desde, $hasta.' 23:59:59'])->count();

        $ventasRegistradas = Venta::whereHas('cliente', fn($q) => $q->whereRaw('"es_mostrador" = false'))
                                   ->whereBetween('fecha', [$desde, $hasta.' 23:59:59'])->count();

        // ✅ Corregido para PostgreSQL
        $clientesPorCiudad = Cliente::select('ciudad', DB::raw('count(*) as total'))
            ->whereRaw('"es_mostrador" = false')
            ->whereNotNull('ciudad')
            ->groupBy('ciudad')
            ->orderByDesc('total')
            ->get();

        return view('admin.reportes.index', compact(
            'desde', 'hasta', 'totalVentas',
            'productosMasVendidos', 'ventasMostrador',
            'ventasRegistradas', 'clientesPorCiudad'
        ));
    }
}