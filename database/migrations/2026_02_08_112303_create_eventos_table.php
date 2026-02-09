<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->foreignId('eventos_tipo_id')
                ->constrained('eventos_tipos');
            $table->foreignId('organizador_id')
                ->constrained('organizadores');
            $table->string('ubicacion', 500)->nullable();
            $table->boolean('activo')->default(true);
            $table->text('notas_cta')->nullable();
            $table->text('notas_servicios')->nullable();
            $table->foreignId('institucion_id')
                ->constrained('instituciones');
            $table->foreignId('usuario_id')
                ->constrained('users');
            $table->timestamps();

            $table->index('created_at', 'idx_evento_fecha_creacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
