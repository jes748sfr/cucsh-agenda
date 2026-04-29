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
        Schema::create('evento_fecha_vals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')
                ->constrained('evento_vals')
                ->cascadeOnDelete();
            $table->foreignId('ubicacion_id')
                ->nullable()
                ->constrained('ubicaciones')
                ->nullOnDelete();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();

            $table->index('fecha', 'idx_fecha_evento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_fecha_vals');
    }
};
