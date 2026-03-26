<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombre_completo',
        'direccion',
        'ciudad',
        'telefono',
        'es_mostrador',
    ];

    protected $casts = [
        'es_mostrador' => 'boolean',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    // Corregido para PostgreSQL
    public static function mostrador()
    {
        return static::whereRaw('"es_mostrador" = true')->first();
    }
}