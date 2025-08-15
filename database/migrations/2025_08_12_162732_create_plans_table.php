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
        Schema::create('plans', function (Blueprint $table) {
              $table->id();
            $table->string('name'); // e.g., Free, Premium
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('storage_limit')->unsigned()->nullable(); // In MB
            $table->integer('photo_upload_limit')->unsigned()->nullable();
            $table->boolean('facial_recognition_enabled')->default(false);
            $table->boolean('merchandise_enabled')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
