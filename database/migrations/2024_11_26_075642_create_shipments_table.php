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
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('courier_name');
            $table->foreignId('pickup_address_id')->constrained('pickup_addresses')->onDelete('cascade');
            $table->decimal('actual_weight', 8, 2);
            $table->decimal('volumetric_weight', 8, 2);
            $table->decimal('chargeable_weight', 8, 2);
            $table->string('platform');
            $table->integer('shipment_id');        
            $table->string('status');
            // store the channel_order_id
            $table->string('channel_order_id')->nullable();    
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
