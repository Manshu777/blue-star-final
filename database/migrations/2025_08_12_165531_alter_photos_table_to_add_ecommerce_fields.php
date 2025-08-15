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
        Schema::table('photos', function (Blueprint $table) {
            // Drop existing columns that don't match the expected structure
            $table->dropColumn(['tag_type', 'tag_value']);
            
            // Add required columns
            $table->string('title')->after('id');
            $table->text('description')->nullable()->after('title');
            $table->string('image_path')->after('description');
            $table->decimal('price', 8, 2)->after('image_path');
            $table->boolean('is_featured')->default(false)->after('price');
            $table->enum('license_type', ['personal', 'commercial'])->nullable()->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_ecommerce_fields', function (Blueprint $table) {
            //
        });
    }
};
