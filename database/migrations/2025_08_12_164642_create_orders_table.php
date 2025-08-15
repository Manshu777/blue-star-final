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
        Schema::create('order', function (Blueprint $table) {
              $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('item_type'); // 'photo' or 'merchandise'
            $table->string('item_id'); // ID of photo or merchandise
            $table->decimal('amount', 8, 2);
            $table->integer('quantity')->default(1);
            $table->string('license_type')->nullable(); // For photos
            $table->string('payment_method');
            $table->text('shipping_address')->nullable(); // For merchandise
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
