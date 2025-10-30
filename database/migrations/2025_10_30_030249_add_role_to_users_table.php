<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'photographer'])->default('user')->after('email'); // Place after email for logical order
            $table->index('role'); // For efficient filtering (e.g., photographers)
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']); // Drop index first
            $table->dropColumn('role');
        });
    }
};