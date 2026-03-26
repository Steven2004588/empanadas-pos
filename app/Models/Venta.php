<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'cliente_id',
        'fecha',
        'total',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'total' => 'decimal:2',
    ];

    // Relación: una venta pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: una venta tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    // Calcula y actualiza el total sumando los subtotales
    public function recalcularTotal()
    {
        $this->total = $this->detalles()->sum('subtotal');
        $this->save();
    }
}