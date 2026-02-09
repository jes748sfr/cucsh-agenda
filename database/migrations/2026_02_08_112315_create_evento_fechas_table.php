<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_fechas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')
                ->constrained('eventos')
                ->cascadeOnDelete();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();

            $table->index('fecha', 'idx_fecha_evento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_fechas');
    }
};
