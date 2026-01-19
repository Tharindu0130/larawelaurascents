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
        Schema::table('products', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['category', 'image_url']);
            
            // Add new columns
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->after('user_id')->constrained()->onDelete('cascade');
            $table->string('image', 500)->nullable()->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['user_id', 'category_id', 'image']);
            $table->string('category', 50)->default('Unisex');
            $table->string('image_url', 500)->nullable();
        });
    }
};
