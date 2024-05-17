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
        Schema::create('producciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_piscina');
            $table->date('fecha');
            $table->decimal('densidad', 10, 2);
            $table->integer('dias_ciclo');
            $table->decimal('peso_transferencia', 10, 2);
            $table->decimal('costo_larva', 10, 2);
            $table->decimal('multiplo_redondeo', 10, 2);
            $table->decimal('supervivencia_30', 10, 2)->nullable();
            $table->decimal('supervivencia_fin', 10, 2)->nullable();
            $table->decimal('capacidad_carga', 10, 2)->nullable();
            $table->decimal('costo_fijo', 10, 2)->nullable();
            $table->boolean('estado')->default(false);
            $table->timestamps();

            $table->foreign('id_piscina')
                ->references('id')
                ->on('piscinas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producciones');
    }
};
