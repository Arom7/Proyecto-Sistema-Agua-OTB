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
        Schema::create('socios',function(Blueprint $table){
            $table->id();
            $table->string('nombre_socio');
            $table->string('primer_apellido_socio');
            $table->string('segundo_apellido_socio')->nullable();
            $table->string('ci_socio');
            $table->unsignedBigInteger('otb_id');
            $table->timestamps();

            $table->foreign('otb_id')
                ->references('id')->on('otbs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
