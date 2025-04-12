<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // id: khóa chính

            $table->string('name'); // tên sản phẩm
            $table->text('descriptions')->nullable(); // mô tả (có thể null)
            $table->decimal('price', 10, 2)->default(0.00); // giá sản phẩm
            $table->integer('stock_quantity')->default(0); // số lượng tồn kho
            $table->string('image_url', 400)->nullable(); // đường dẫn ảnh
            $table->timestamp('created_at')->nullable(); // thời gian tạo sản phẩm
            $table->unsignedBigInteger('category_id'); // khóa ngoại đến bảng categories

            // Thiết lập khóa ngoại
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
