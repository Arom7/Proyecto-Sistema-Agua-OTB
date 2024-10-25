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
        Schema::create('preavisos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_lectura');
            $table->boolean('estado_pago')->default(false);
            $table->string('observaciones')->nullable();
            $table->integer('lectura_actual_correspondiente');
            $table->integer('lectura_anterior_correspondiente');
            $table->double('total');
            $table->unsignedBigInteger('id_consumo_recibo')->unique();

            $table->foreign('id_consumo_recibo')
                ->references('id_consumo')->on('consumos')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibos');
    }
};
