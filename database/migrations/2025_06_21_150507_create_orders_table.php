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
            $table->decimal('discount', 10, 2)->default(0.00); // số tiền giảm giá
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending'); // trạng thái đơn hàng
            $table->timestamps();
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


