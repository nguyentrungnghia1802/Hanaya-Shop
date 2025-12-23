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
            // Index for category filtering
            $table->index('category_id');

            // Index for sorting by price
            $table->index('price');

            // Index for sorting by discount
            $table->index('discount_percent');

            // Index for sorting by view count
            $table->index('view_count');

            // Index for sorting by creation date
            $table->index('created_at');

            // Composite index for category + price (common filtering combination)
            $table->index(['category_id', 'price']);

            // Composite index for discount + price (sale products)
            $table->index(['discount_percent', 'price']);

            // Full text search index for name and descriptions (only for MySQL)
            if (config('database.default') === 'mysql') {
                $table->fullText(['name', 'descriptions']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['price']);
            $table->dropIndex(['discount_percent']);
            $table->dropIndex(['view_count']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['category_id', 'price']);
            $table->dropIndex(['discount_percent', 'price']);

            // Drop fulltext index only for MySQL
            if (config('database.default') === 'mysql') {
                $table->dropFullText(['name', 'descriptions']);
            }
        });
    }
};
