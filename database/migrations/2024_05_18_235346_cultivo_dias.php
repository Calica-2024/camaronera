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
        Schema::create('cultivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produccion');
            $table->date('fecha');
            $table->string('dia');
            $table->decimal('pero_proyecto', 10, 2)->nullable();
            $table->decimal('crecimiento_lineal', 10, 2)->nullable();
            $table->decimal('supervivencia_base', 10, 2)->nullable();
            $table->decimal('densidad', 10, 2)->nullable();
            $table->decimal('biomasa_raleada', 10, 2)->nullable();
            $table->decimal('biomasa', 10, 2)->nullable();
            $table->decimal('peso_corporal', 10, 2)->nullable();
            $table->decimal('alimento_dia', 10, 2)->nullable();
            $table->decimal('alimento_area', 10, 2)->nullable();
            $table->unsignedBigInteger('id_balanceado');
            $table->decimal('alimento_aculumado', 10, 2);
            $table->decimal('fca', 10, 2)->nullable();
            $table->decimal('roi', 10, 2)->nullable();
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
        Schema::dropIfExists('cultivos');
    }
};
