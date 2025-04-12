<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppliedCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('applied_coupons', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY, AUTO_INCREMENT
            $table->timestamp('applied_at')->nullable(); // nullable timestamp
            $table->unsignedBigInteger('order_id'); // foreign key to orders
            $table->unsignedBigInteger('coupon_id'); // foreign key to coupons

            // Nếu bạn đã có bảng orders và coupons, nên thêm khóa ngoại
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applied_coupons');
    }
}
;
