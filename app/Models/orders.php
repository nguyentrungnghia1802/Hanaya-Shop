<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'total_price',
        'status',
    ];

    protected $date = [
        'created_at',
    ];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo(Users::class);
    }

    public function appliedCoupon(){
        return $this->hasMany(AppliedCoupons::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function payment()
    {
        return $this->hasMany(Payments::class);
    }
}
