<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product\Product;
use App\Models\Order\Order;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'rating',
        'comment',
        'product_id',
        'order_id',
        'user_id',
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    // Quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Accessor cho rating - nếu null thì trả về 5
    public function getRatingAttribute($value)
    {
        return $value ?? 5;
    }

    // Scope để lấy review theo product
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // Scope để lấy review theo user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
