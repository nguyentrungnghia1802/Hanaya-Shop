<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', true)->orderByDesc('created_at')->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->where('status', true)->firstOrFail();
        return view('posts.show', compact('post'));
    }
}
