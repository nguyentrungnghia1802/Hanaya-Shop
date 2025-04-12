<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppliedCoupon extends Model
{
    use HasFactory;

    // Tên bảng trong database (không cần nếu tên bảng theo chuẩn số nhiều)
    protected $table = 'applied_coupons';

    // Tự động quản lý timestamps (created_at, updated_at) => false vì không có cột này
    public $timestamps = false;

    // Các trường cho phép gán hàng loạt
    protected $fillable = [
        'order_id',
        'coupon_id',
    ];

    // Các kiểu dữ liệu cần được cast tự động
    protected $casts = [
        'applied_at' => 'datetime',
    ];

    /**
     * Mỗi applied coupon thuộc về một đơn hàng (order).
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mỗi applied coupon thuộc về một mã giảm giá (coupon).
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
