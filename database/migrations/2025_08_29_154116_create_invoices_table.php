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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->date('billing_month');
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('water_fee', 10, 2)->default(0);
            $table->decimal('electric_fee', 10, 2)->default(0);
            $table->decimal('water_usage_amount', 10, 2)->default(0);
            $table->decimal('electric_usage_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
