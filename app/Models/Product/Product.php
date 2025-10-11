<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\OrderDetail;
use App\Models\Product\Category;
use App\Models\Cart\Cart;


class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'descriptions',
        'price',
        'stock_quantity',
        'image_url',
        'category_id',
    ];

    protected $dates = [
        'created_at',
    ];

    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getDiscountedPrice($discountPercentage)
    {
        return $this->price - ($this->price * $discountPercentage / 100);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}
