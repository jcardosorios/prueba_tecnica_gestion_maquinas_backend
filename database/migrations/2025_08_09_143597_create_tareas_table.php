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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produccion')->nullable()->constrained('produccions');
            $table->foreignId('id_maquina')->constrained('maquinas');
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_termino')->nullable();
            $table->decimal('tiempo_empleado',5,2)->nullable();
            $table->decimal('tiempo_produccion',5,2)->nullable();
            $table->enum('estado', ['PENDIENTE', 'COMPLETADA'])->DEFAULT('PENDIENTE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
