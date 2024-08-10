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
        Schema::create('telemetria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_p_real');
            $table->time('hora1')->nullable();
            $table->decimal('temperatura1', 10, 2)->nullable();
            $table->decimal('oxigeno1', 10, 2)->nullable();
            $table->time('hora2')->nullable();
            $table->decimal('temperatura2', 10, 2)->nullable();
            $table->decimal('oxigeno2', 10, 2)->nullable();
            $table->time('hora3')->nullable();
            $table->decimal('temperatura3', 10, 2)->nullable();
            $table->decimal('oxigeno3', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('id_p_real')
                ->references('id')
                ->on('proyecto_real');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetria');
    }
};
