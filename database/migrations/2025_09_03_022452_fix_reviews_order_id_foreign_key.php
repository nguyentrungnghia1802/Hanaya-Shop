<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sửa lỗi khóa ngoại từ reviews.order_id sang order.id thay vì products.id
     */
    public function up(): void
    {
        // Xóa khóa ngoại hiện tại nếu có
        Schema::table('reviews', function (Blueprint $table) {
            // Kiểm tra nếu tồn tại khóa ngoại
            if (Schema::hasColumn('reviews', 'order_id') && 
                DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME = 'reviews' AND COLUMN_NAME = 'order_id'
                    AND REFERENCED_TABLE_NAME = 'products'")) {
                
                $table->dropForeign(['order_id']);
            }
        });

        // Tạo khóa ngoại đúng tới bảng orders thay vì products
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            // Khôi phục lại khóa ngoại cũ (tuy không đúng)
            $table->foreign('order_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
