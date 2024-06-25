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
        Schema::table('producciones', function (Blueprint $table) {
            $table->decimal('crecimiento1', 10, 2)->after('costo_fijo');
            $table->decimal('crecimiento2', 10, 2)->after('costo_fijo');
            $table->decimal('crecimiento3', 10, 2)->after('costo_fijo');
            $table->decimal('crecimiento4', 10, 2)->after('costo_fijo');
            $table->decimal('crecimiento5', 10, 2)->after('costo_fijo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
