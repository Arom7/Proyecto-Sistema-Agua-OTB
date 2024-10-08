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
        Schema::create('consumos', function (Blueprint $table) {
            $table->id('id_consumo');
            $table->date('mes_correspondiente');
            $table->integer('lectura_actual');
            $table->integer('consumo_total');
            $table->string('propiedad_id_consumo');
            $table->timestamps();

            $table->foreign('propiedad_id_consumo')
                ->references('propiedad_id_medidor')->on('medidores')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumos');
    }
};
