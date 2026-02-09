<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos_fechas', function (Blueprint $table) {
            /*
             Indice compuesto para la validacion de solapamiento.
             La consulta filtra por fecha exacta y luego compara
             rangos de hora_inicio/hora_fin. Este indice permite
             que MariaDB resuelva las 3 condiciones sin table scan.
            */
            $table->index(
                ['fecha', 'hora_inicio', 'hora_fin'],
                'idx_solapamiento'
            );
        });
    }

    public function down(): void
    {
        Schema::table('eventos_fechas', function (Blueprint $table) {
            $table->dropIndex('idx_solapamiento');
        });
    }
};
