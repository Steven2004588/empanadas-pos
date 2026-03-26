<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $existe = DB::table('clientes')
            ->whereRaw('"es_mostrador" = true')
            ->exists();

        if (! $existe) {
            DB::table('clientes')->insert([
                'tipo_documento'   => 'cedula',
                'numero_documento' => '0000000000',
                'nombre_completo'  => 'Cliente Mostrador',
                'direccion'        => null,
                'ciudad'           => null,
                'telefono'         => null,
                'es_mostrador'     => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
            $this->command->info('  Cliente de mostrador creado.');
        } else {
            $this->command->warn('  Cliente de mostrador ya existe, se omitió.');
        }
    }
}