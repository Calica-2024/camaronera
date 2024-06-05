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
        Schema::create('piscinas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('numero');
            $table->unsignedBigInteger('id_camaronera');
            $table->decimal('area_ha', 10, 2);
            $table->boolean('estado')->default(false);
            $table->timestamps();

            $table->foreign('id_camaronera')
                ->references('id')
                ->on('camaroneras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piscinas');
    }
};
