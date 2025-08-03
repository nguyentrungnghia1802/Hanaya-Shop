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
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // Remove user_id column if it exists (Laravel default doesn't need it)
            if (Schema::hasColumn('password_reset_tokens', 'user_id')) {
                // Drop foreign key constraint first if it exists
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // Add back user_id column if needed (but this shouldn't be necessary)
            $table->unsignedBigInteger('user_id')->nullable()->after('created_at');
        });
    }
};
