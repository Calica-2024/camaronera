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
        Schema::create('proyecto_real', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produccion');
            $table->date('fecha');
            $table->string('dia');
            $table->integer('num_dia');
            $table->decimal('peso_real', 10, 2)->nullable();
            $table->decimal('peso_real_anterior', 10, 2)->nullable();
            $table->decimal('alimento', 10, 2)->nullable();
            $table->decimal('alimento_calculo', 10, 2)->nullable();
            $table->decimal('peso_corporal', 10, 2)->nullable();
            $table->decimal('densidad_consumo', 10, 2)->nullable();
            $table->decimal('densidad_muestreo', 10, 2)->nullable();
            $table->decimal('densidad_actual', 10, 2)->nullable();
            $table->decimal('supervivencia', 10, 2)->nullable();
            $table->decimal('densidad_raleada', 10, 2)->nullable();
            $table->decimal('densidad_raleada_acumulada', 10, 2)->nullable();
            $table->decimal('biomasa_raleada', 10, 2)->nullable();
            $table->decimal('biomasa_actual', 10, 2)->nullable();
            $table->decimal('recomendacion_alimento', 10, 2)->nullable();
            $table->decimal('alimento_acumulado', 10, 2)->nullable();
            $table->decimal('fca', 10, 2)->nullable();
            $table->decimal('costo_biomasa', 10, 2)->nullable();
            $table->decimal('up', 10, 2)->nullable();
            $table->decimal('roi', 10, 2)->nullable();
            $table->unsignedBigInteger('id_balanceado');
            $table->timestamps();

            $table->foreign('id_produccion')
                ->references('id')
                ->on('producciones');

            $table->foreign('id_balanceado')
                ->references('id')
                ->on('balanceados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_real');
    }
};
