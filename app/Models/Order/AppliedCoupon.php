<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppliedCoupon extends Model
{
    use HasFactory;

    protected $table = 'applied_coupons';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'coupon_id',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
