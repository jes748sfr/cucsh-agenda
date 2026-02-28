<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eliminar la columna ubicacion (varchar) que fue reemplazada por ubicacion_id (FK).
     */
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('ubicacion');
        });
    }

    /**
     * Restaurar la columna ubicacion en caso de rollback.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('ubicacion')->nullable()->after('organizador_id');
        });
    }
};
