<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
