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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Liên kết đến bảng products
            $table->unsignedBigInteger('product_id');

            // Người dùng đã đăng nhập (nullable nếu không login)
            $table->unsignedBigInteger('user_id')->nullable();

            // Người dùng chưa đăng nhập thì dùng session_id
            $table->string('session_id')->nullable();

            // Số lượng sản phẩm
            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
