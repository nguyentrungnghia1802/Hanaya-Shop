<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content', 'image', 'status', 'user_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Xóa ảnh featured khi xóa post
     */
    public function deleteFeaturedImage()
    {
        if ($this->image) {
            $imagePath = public_path("images/posts/{$this->image}");
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
    }

    /**
     * Xóa tất cả ảnh trong content khi xóa post
     */
    public function deleteContentImages()
    {
        if ($this->content) {
            // Tìm tất cả ảnh trong content
            preg_match_all('/src="[^"]*images\/posts\/([^"]*)"/', $this->content, $matches);
            
            if (!empty($matches[1])) {
                foreach ($matches[1] as $imageName) {
                    $imagePath = public_path("images/posts/{$imageName}");
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
            }
        }
    }

    /**
     * Boot method để tự động xóa ảnh khi xóa post
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->deleteFeaturedImage();
            $post->deleteContentImages();
        });
    }
}
