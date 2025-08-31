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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            $table->string('watermarked_path')->nullable()->after('image_path');
            $table->string('tags', 500)->nullable()->after('license_type');
            $table->json('metadata')->nullable()->after('tags');
            $table->string('tour_provider', 255)->nullable()->after('metadata');
            $table->string('location', 255)->nullable()->after('tour_provider');
            $table->string('event', 255)->nullable()->after('location');
            $table->dateTime('date')->nullable()->after('event');
            $table->float('file_size')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'watermarked_path',
                'tags',
                'metadata',
                'tour_provider',
                'location',
                'event',
                'date',
                'file_size',
            ]);
        });
    }
};
