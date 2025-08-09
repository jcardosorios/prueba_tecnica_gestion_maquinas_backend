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
        Schema::create('produccion', function (Blueprint $table) {
            $table->id();
            $table->decimal('tiempo_produccion',5,2);
            $table->decimal('tiempo_inactividad',5,2);
            $table->dateTime('fecha_hora_inicio_inactividad',5,2);
            $table->dateTime('fecha_hora_termino_inactividad',5,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produccion');
    }
};
