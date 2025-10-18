<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderByDesc('created_at')->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with('author')->findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'status' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Tạo tên file unique với timestamp
            $timestamp = Carbon::now()->format('YmdHis');
            $randomString = Str::random(8);
            $extension = $file->getClientOriginalExtension();
            $filename = "post_featured_{$timestamp}_{$randomString}.{$extension}";
            
            // Tạo thư mục nếu chưa có
            $uploadPath = public_path('images/posts');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Di chuyển file
            $file->move($uploadPath, $filename);
            $imagePath = $filename; // Chỉ lưu tên file, không lưu đường dẫn đầy đủ
        }

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'image' => $imagePath,
            'status' => $request->input('status', true),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.post.index')->with('success', 'Tạo bài viết thành công');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.create', ['edit' => true, 'post' => $post]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($post->image) {
                $oldImagePath = public_path("images/posts/{$post->image}");
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            
            $file = $request->file('image');
            
            // Tạo tên file unique với timestamp
            $timestamp = Carbon::now()->format('YmdHis');
            $randomString = Str::random(8);
            $extension = $file->getClientOriginalExtension();
            $filename = "post_featured_{$timestamp}_{$randomString}.{$extension}";
            
            // Tạo thư mục nếu chưa có
            $uploadPath = public_path('images/posts');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Di chuyển file
            $file->move($uploadPath, $filename);
            $post->image = $filename; // Chỉ lưu tên file
        }

        $post->title = $validated['title'];
        $post->slug = Str::slug($validated['title']);
        $post->content = $validated['content'];
        $post->status = $request->input('status', true);
        $post->save();

        return redirect()->route('admin.post.index')->with('success', 'Cập nhật bài viết thành công');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.post.index')->with('success', 'Xóa bài viết thành công');
    }

}
