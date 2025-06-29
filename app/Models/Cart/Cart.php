<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product\Product;
use App\Models\User;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts'; // Bảng lưu giỏ hàng

    protected $fillable = [
        'product_id',
        'user_id',     // Nếu người dùng đã đăng nhập
        'session_id',  // Nếu chưa đăng nhập
        'quantity',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public $timestamps = true;

    /**
     * Quan hệ: Giỏ hàng thuộc về 1 sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ: Giỏ hàng thuộc về 1 người dùng (nếu login)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
