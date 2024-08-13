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
        Schema::create('movimientos_balanceado', function (Blueprint $table) {
            $table->id(); // Identificador único del movimiento
            $table->foreignId('id_camaronera')->constrained('camaroneras'); // Relación con la tabla balanceados
            $table->foreignId('id_balanceado')->constrained('balanceados'); // Relación con la tabla balanceados
            $table->enum('tipo_movimiento', ['entrada', 'salida']); // Tipo de movimiento
            $table->decimal('cantidad', 10, 2); // Cantidad de balanceado movida
            $table->foreignId('id_p_real')->nullable()->constrained('proyecto_real')->nullOnDelete(); // Relación con la tabla produccion, nullable
            $table->timestamp('fecha_movimiento')->default(now()); // Fecha y hora del movimiento
            $table->string('descripcion')->nullable(); // Descripción o razón del movimiento
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_balanceado');
    }
};
