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
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('system_id')->unique()->nullable();
            $table->string('location')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('tracking_system_working')->default(1);
            $table->tinyInteger('water_level')->default(0);
            $table->timestamp('next_clean')->nullable();
            $table->integer('next_clean_after')->nullable()->comment('in minutes');
            $table->boolean('cleaning')->default(0);
            $table->tinyInteger('temperature')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('systems');
    }
};
