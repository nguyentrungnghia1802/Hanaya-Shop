<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'rating',
        'comment',
    ];

    protected $date = [
        'created_at',
    ];

    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
