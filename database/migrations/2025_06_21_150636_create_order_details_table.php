<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id(); // khóa chính tự tăng
            $table->integer('quantity'); // số lượng sản phẩm trong đơn
            $table->decimal('price', 10, 2)->default(0.00); // giá tại thời điểm mua
            $table->unsignedBigInteger('product_id'); // khóa ngoại đến bảng products
            $table->unsignedBigInteger('order_id');   // khóa ngoại đến bảng orders
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            // Nếu muốn kết hợp product_id + order_id thành UNIQUE (để tránh trùng)
            // $table->unique(['product_id', 'order_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
