<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupons extends Model
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
        return $this->hasMany(AppliedCoupons::class);
    }
}
