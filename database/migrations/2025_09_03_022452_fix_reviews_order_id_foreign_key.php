<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fix duplicate foreign key constraint issue for CI/CD
     */
    public function up(): void
    {
        // Check if foreign key constraint already exists before creating
        $constraintExists = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'reviews' 
            AND CONSTRAINT_NAME = 'reviews_order_id_foreign'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");

        if ($constraintExists[0]->count == 0) {
            // Only create foreign key if it doesn't exist
            Schema::table('reviews', function (Blueprint $table) {
                $table->foreign('order_id', 'reviews_order_id_foreign')
                    ->references('id')->on('orders')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if foreign key exists before dropping
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'order_id')) {
                $table->dropForeign(['order_id']);
            }
        });
    }
};
