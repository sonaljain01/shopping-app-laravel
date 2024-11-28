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
        Schema::table('pickup_addresses', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->dropColumn('zip');
            $table->string('pincode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pickup_addresses', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->string('zip');
            $table->dropColumn('pincode');
        });
    }
};
