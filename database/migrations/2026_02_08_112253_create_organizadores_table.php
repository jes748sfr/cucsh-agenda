<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('tel', 20)->nullable();
            $table->string('email', 255);
            $table->foreignId('administracion_id')
                ->constrained('administraciones')
                ->restrictOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('email', 'uk_organizador_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizadores');
    }
};
