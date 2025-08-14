<?php

namespace App\Http\Controllers\User;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('status', true)->with('author');
        
        // Xử lý tìm kiếm
        if ($request->has('search') && $request->input('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        $posts = $query->orderByDesc('created_at')->paginate(10);
        
        // Preserve search parameters in pagination
        if ($request->has('search')) {
            $posts->appends(['search' => $request->input('search')]);
        }
        
        return view('page.posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::where('id', $id)->where('status', true)->with('author')->firstOrFail();
        return view('page.posts.show', compact('post'));
    }
}
