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
        Schema::create('propiedades', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('socio_id')->nullable();
            $table->string('direccion_propiedad');
            $table->double('total_multas_propiedad');
            $table->string('descripcion_propiedad')->nullable();
            $table->timestamps();

            $table->foreign('socio_id')
                ->references('id')->on('socios')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propiedades');
    }
};
