<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            if (!Schema::hasColumn('photos', 'original_path')) {
                $table->string('original_path')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('photos', 'is_sold')) {
                $table->boolean('is_sold')->default(false)->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn(['original_path', 'is_sold']);
        });
    }
};
