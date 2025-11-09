<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'address_id')) {
                $table->unsignedBigInteger('address_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'message')) {
                $table->string('message')->nullable()->after('status');
            }
        });
        // Thêm foreign key nếu chưa có (Laravel không có hàm kiểm tra foreign key, nên cần xử lý thủ công nếu cần)
        // Nếu migration fail do foreign key đã tồn tại, hãy xóa thủ công foreign key trong DB
        Schema::table('orders', function (Blueprint $table) {
            // Chỉ thêm foreign nếu cột address_id vừa được tạo
            // Nếu đã có, sẽ bị lỗi duplicate key, cần xử lý thủ công nếu cần
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn(['address_id', 'message']);
        });
    }
};
