<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evento_vals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->foreignId('eventos_tipo_id')
                ->constrained('eventos_tipos');
            $table->foreignId('organizador_id')
                ->constrained('organizadores');
            $table->foreignId('ubicacion_id')
                ->nullable()
                ->constrained('ubicaciones')
                ->nullOnDelete();
            $table->boolean('activo')->default(true);
            $table->string('color', 7)->default('#7FBCD2');
            $table->text('notas_cta')->nullable();
            $table->text('notas_servicios')->nullable();
            $table->foreignId('institucion_id')
                ->constrained('instituciones');
            $table->foreignId('usuario_id')
                ->constrained('users');
            $table->boolean('validado')->default(false);
            $table->timestamps();

            $table->index('created_at', 'idx_evento_fecha_creacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_vals');
    }
};
