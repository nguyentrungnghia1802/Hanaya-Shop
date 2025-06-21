<?php

namespace App\Models\Offer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discount',
    ];

    protected $date = [
        'expiration_data',
    ];

    public function appliedCoupons()
    {
        return $this->hasMany(AppliedCoupon::class);
    }
}
