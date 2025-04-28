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
        Schema::create('power_predicteds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cell_id')->nullable()->constrained('cells')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->integer('power_actual')->default(0);
            $table->integer('power_predicted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_predicteds');
    }
};
