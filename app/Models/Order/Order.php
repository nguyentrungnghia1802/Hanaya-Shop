<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use App\Models\Product\Review;
use App\Models\Address;



class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'message',
        'address_id',
    ];

    // Accessor for total_amount (if used in views)
    public function getTotalAmountAttribute()
    {
        return $this->total_price;
    }

    // Mutator for total_amount
    public function setTotalAmountAttribute($value)
    {
        $this->attributes['total_price'] = $value;
    }

    protected $date = [
        'created_at',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }


}
