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
        Schema::table('addresses', function (Blueprint $table) {
            $table->renameColumn('username', 'name');
            $table->enum('type', ['billing', 'shipping'])->default('billing');
            $table->boolean('is_default')->default(true);
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->dropColumn('email');
            $table->dropForeign(['order_id']); // Drop the foreign key constraint
            $table->dropColumn('order_id'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->renameColumn('name', 'username');
            $table->dropColumn(['type', 'is_default', 'user_id']);
            $table->string('email');
            $table->foreignId('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }
};