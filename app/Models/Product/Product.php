<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\OrderDetail;
use App\Models\Product\Category;
use App\Models\Cart\Cart;
use App\Models\Product\Review;


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
        'discount_percent',
        'view_count',
    ];

    protected $dates = [
        'created_at',
    ];

    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getDiscountedPrice($discountPercentage = null)
    {
        $discount = $discountPercentage ?? $this->discount_percent;
        return $this->price - ($this->price * $discount / 100);
    }

    // Accessor for discounted price
    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_percent > 0) {
            return $this->price - ($this->price * $this->discount_percent / 100);
        }
        return $this->price;
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Get average rating for this product
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 5;
    }

    // Get total review count
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }
}
