<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 20)->default('cedula');
            $table->string('numero_documento', 20)->unique();
            $table->string('nombre_completo', 150);
            $table->string('direccion', 200)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->boolean('es_mostrador')->default(false);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE clientes ADD CONSTRAINT clientes_tipo_documento_check
            CHECK (tipo_documento IN ('cedula','tarjeta_identidad','pasaporte','nit'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};