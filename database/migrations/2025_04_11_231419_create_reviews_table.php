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
            $table->tinyInteger('rating')->nullable(); // điểm đánh giá từ 1-5, có thể null
            $table->text('comment')->nullable();       // nội dung đánh giá
            $table->timestamp('created_at')->nullable(); // thời gian đánh giá
            $table->unsignedBigInteger('product_id'); // liên kết đến bảng products
            $table->unsignedBigInteger('user_id');    // liên kết đến bảng users

            // Khóa ngoại
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
