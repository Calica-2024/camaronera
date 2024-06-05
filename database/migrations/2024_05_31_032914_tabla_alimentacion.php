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
        Schema::create('tabla_alimentacion', function (Blueprint $table) {
            $table->id();
            $table->decimal('pesos', 10, 2);
            $table->decimal('ta1', 10, 2)->nullable();
            $table->decimal('ta2', 10, 2)->nullable();
            $table->decimal('ta3', 10, 2)->nullable();
            $table->decimal('ta4', 10, 2)->nullable();
            $table->decimal('ta5', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabla_alimentacion');
    }
};
