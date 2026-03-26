<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('productos')->count() > 0) {
            $this->command->warn('  La tabla productos ya tiene registros, se omitió.');
            return;
        }

        DB::table('productos')->insert([
            [
                'nombre'      => 'Empanada de pipián',
                'descripcion' => 'Empanada rellena de pipián',
                'precio'      => 2500.00,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Empanada de pollo',
                'descripcion' => 'Empanada rellena de pollo',
                'precio'      => 2500.00,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Empanada de carne',
                'descripcion' => 'Empanada rellena de carne molida',
                'precio'      => 2500.00,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Papa rellena de pollo',
                'descripcion' => 'Papa rellena con guiso de pollo',
                'precio'      => 3000.00,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Papa rellena de carne',
                'descripcion' => 'Papa rellena con carne molida',
                'precio'      => 3000.00,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        $this->command->info('  5 productos de ejemplo creados.');
    }
}