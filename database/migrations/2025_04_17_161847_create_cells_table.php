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
        Schema::create('cells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->nullable()->references('systems')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('cell_id')->unique()->nullable();
            $table->integer('current')->default(0);
            $table->integer('voltage')->default(0);
            $table->integer('power')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cells');
    }
};
