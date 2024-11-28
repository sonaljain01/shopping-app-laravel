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
            $table->renameColumn('address_1', 'address');
            $table->dropColumn('address_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pickup_addresses', function (Blueprint $table) {
            $table->renameColumn('address', 'address_1');
            $table->string('address_2')->nullable();
        });
    }
};
