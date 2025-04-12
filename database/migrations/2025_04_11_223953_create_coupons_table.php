<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id('id'); 
            $table->string('code', 50)->unique(); // Mã giảm giá, unique, not null
            $table->double('discount')->default(0); // Tỉ lệ/giá trị giảm giá, có thể null, mặc định = 0
            $table->date('expiration_date')->nullable(); // Ngày hết hạn, cho phép null
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
;
