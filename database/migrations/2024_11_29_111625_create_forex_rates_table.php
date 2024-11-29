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
        Schema::create('forex_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency', 3); // Base currency, e.g., USD
            $table->string('target_currency', 3); // Target currency, e.g., INR, EUR
            $table->decimal('rate', 10, 4); // Conversion rate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forex_rates');
    }
};
