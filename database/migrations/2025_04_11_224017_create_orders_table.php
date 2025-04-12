<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // id: khóa chính, auto_increment
            $table->decimal('total_price', 10, 2)->default(0.00); // tổng tiền đơn hàng
            $table->enum('status', ['pending', 'processing', 'shipped'])->default('pending'); // trạng thái đơn hàng
            $table->timestamp('created_at')->nullable(); // thời gian tạo đơn hàng (có thể dùng timestamps() cũng được)
            $table->unsignedBigInteger('user_id'); // khóa ngoại liên kết tới bảng users

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
