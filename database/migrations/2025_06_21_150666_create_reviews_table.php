<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // ID tự tăng
            $table->tinyInteger('rating')->default(5); // điểm đánh giá từ 1-5, mặc định 5
            $table->longText('comment')->nullable();   // nội dung đánh giá
            $table->string('image_path')->nullable(); // image: varchar(255), nullable
            $table->timestamps();
            $table->unsignedBigInteger('product_id');  // liên kết đến bảng products
            $table->unsignedBigInteger('order_id');    // liên kết đến bảng orders
            $table->unsignedBigInteger('user_id');     // liên kết đến bảng users

            // Khóa ngoại
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Đảm bảo mỗi user chỉ có thể review 1 lần cho 1 product trong 1 order
            $table->unique(['user_id', 'product_id', 'order_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
