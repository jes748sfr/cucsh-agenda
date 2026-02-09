<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_tipos');
    }
};
