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
        Schema::create('utility_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->date('reading_date');
            $table->decimal('water_meter_start', 10, 2)->default(0);
            $table->decimal('water_meter_end', 10, 2)->default(0);
            $table->decimal('electric_meter_start', 10, 2)->default(0);
            $table->decimal('electric_meter_end', 10, 2)->default(0);
            $table->foreignId('utility_rate_id')->constrained();
            $table->string('water_meter_image_start')->nullable();
            $table->string('water_meter_image_end')->nullable();
            $table->string('electric_meter_image_start')->nullable();
            $table->string('electric_meter_image_end')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_usages');
    }
};
