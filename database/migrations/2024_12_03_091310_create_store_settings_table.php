<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('store_address');
            $table->string('store_city');
            $table->string('store_state');
            $table->string('store_pin');
            $table->string('store_country');
            $table->string('store_phone');
            $table->string('gst_number')->nullable();
            $table->enum('tax_type', ['no_tax', 'inclusive', 'exclusive'])->default('no_tax');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
