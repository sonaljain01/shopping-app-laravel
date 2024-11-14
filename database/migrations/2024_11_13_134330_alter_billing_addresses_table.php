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
        Schema::table('billing_addresses', function (Blueprint $table) {
            // change first_name to username
            $table->renameColumn('first_name', 'username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_addresses', function (Blueprint $table) {
            $table->renameColumn('username', 'first_name');
        });
    }
};