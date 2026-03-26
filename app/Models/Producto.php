<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    // ✅ Mutador que fuerza el tipo booleano nativo antes de ir a PostgreSQL
    protected function setActivoAttribute($value)
    {
        $this->attributes['activo'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? true : false;
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function scopeActivos($query)
    {
        return $query->whereRaw('"activo" = true');
    }

    public function fueVendido(): bool
    {
        return $this->detalles()->exists();
    }
}