<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
