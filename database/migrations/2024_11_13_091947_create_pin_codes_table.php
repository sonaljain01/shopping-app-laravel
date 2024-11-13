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
        Schema::create('pin_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->references('id')->on('cities')->onDelete('cascade');  // Linking to the cities table
            $table->string('pincode');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pin_codes');
    }
};
