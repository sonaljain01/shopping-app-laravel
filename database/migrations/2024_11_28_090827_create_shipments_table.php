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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->foreignId('channel_order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('shipment_id');
            $table->string('courier_name');
            $table->string('status');
            $table->foreignId('pickup_address_id')->constrained('pickup_addresses')->onDelete('cascade');
            $table->decimal('actual_weight', 8, 2)->nullable();
            $table->decimal('volumetric_weight', 8, 2)->nullable();
            $table->string('platform');
            $table->float('charges')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
