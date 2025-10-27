<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Address extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'user_id',
        'phone_number',
        'address',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
    ];
}
