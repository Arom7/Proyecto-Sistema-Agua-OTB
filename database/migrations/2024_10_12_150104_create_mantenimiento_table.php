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
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table -> date('fecha_mantenimiento_inicio');
            $table -> date('fecha_mantenimiento_fin');
            $table -> string('descripcion_mantenimiento');
            $table -> string('responsable');
            $table -> double('precio_total');
            $table -> string('tipo_equipo');
            $table -> date('fecha_proximo_mantenimiento');
            $table -> unsignedBigInteger('otb_id');

            $table->foreign('otb_id')
                ->references('id')->on('otbs')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimiento');
    }
};
