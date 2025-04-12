<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'quantity',
        'price',
    ];

    public function product(){
        return $this->belongsTo(Products::class);
    }

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
    
}
