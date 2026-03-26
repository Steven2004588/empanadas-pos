<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal'        => 'decimal:2',
    ];

    // Relación: el detalle pertenece a una venta
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // Relación: el detalle pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}