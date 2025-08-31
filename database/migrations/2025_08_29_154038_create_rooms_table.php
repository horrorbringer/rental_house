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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->string('room_number', 50);
            $table->decimal('monthly_rent', 10, 2);
            $table->decimal('water_fee', 10, 2)->default(0);
            $table->decimal('electric_fee', 10, 2)->default(0);
            $table->unsignedInteger('capacity')->default(1); // number of tenants allowed
            $table->string('image')->nullable(); // main image for the room
            $table->enum('status', ['vacant', 'occupied'])->default('vacant');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
