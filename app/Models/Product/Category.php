<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'image_path',
    ];

    protected $date = [
        'created_at',
        'updated_at',
    ];
    public $timestamps = true;

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
