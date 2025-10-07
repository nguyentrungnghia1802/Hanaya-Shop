<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    // Khai báo bảng tương ứng trong cơ sở dữ liệu
    protected $table = 'products';

    // Khai báo các thuộc tính có thể gán hàng loạt (mass assignable)
    protected $fillable = [
        'name',
        'descriptions',
        'price',
        'stock_quantity',
        'image_url',
        'category_id',
    ];

    // Khai báo các thuộc tính kiểu thời gian
    protected $dates = [
        'created_at',
    ];

    // Xử lý 'created_at', tự động quản lý 'created_at' khi insert/update
    public $timestamps = true;

    /**
     * Mối quan hệ với bảng Category (một sản phẩm thuộc về một danh mục)
     */
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    /**
     * Hàm tính giá sau giảm giá (nếu có)
     */
    public function getDiscountedPrice($discountPercentage)
    {
        return $this->price - ($this->price * $discountPercentage / 100);
    }
}
