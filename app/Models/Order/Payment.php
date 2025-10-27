<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_method',
        'payment_status',
        'transaction_id',
        'order_id',
        
    ];

    protected $date = [
        'created_at',
    ];

    public $timestamps = true;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function getAvailableMethods(): array
    {
        return ['cash_on_delivery', 'credit_card', 'paypal'];
    }
}
