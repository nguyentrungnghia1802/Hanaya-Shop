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
    /**
     * Display a paginated list of posts with optional search by title or content.
     * Preserves search parameters in pagination.
     * Returns the posts to the index view.
     */
    public function index(Request $request)
    {
        $query = Post::query()->with('author');
        
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
        
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show detailed information for a specific post, including author.
     * Returns the post to the show view.
     */
    public function show($id)
    {
        $post = Post::with('author')->findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form to create a new post.
     * Returns the create view.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a new post in the database after validating input.
     * Handles image upload and generates a safe slug.
     * Redirects to the post index with a success message.
     */
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

        // Tạo slug an toàn cho các ký tự đặc biệt
        $slug = $this->createSafeSlug($validated['title']);

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'image' => $imagePath,
            'status' => $request->input('status', true),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.post.index')->with('success', __('admin.post_created_successfully'));
    }

    /**
     * Show the form to edit an existing post.
     * Returns the create view with edit mode and post data.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.create', ['edit' => true, 'post' => $post]);
    }

    /**
     * Update an existing post in the database after validating input.
     * Handles image replacement and slug update.
     * Redirects to the post index with a success message.
     */
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
        $post->slug = $this->createSafeSlug($validated['title'], $post->id);
        $post->content = $validated['content'];
        $post->status = $request->input('status', true);
        $post->save();

        return redirect()->route('admin.post.index')->with('success', __('admin.post_updated_successfully'));
    }

    /**
     * Delete a post and its associated images from the database and filesystem.
     * Redirects to the post index with a success message.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        // Delete featured image if exists
        if ($post->image) {
            $imagePath = public_path("images/posts/{$post->image}");
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        
        // Extract and delete images from content
        if ($post->content) {
            $this->deleteImagesFromContent($post->content);
        }
        
        $post->delete();
        return redirect()->route('admin.post.index')->with('success', __('admin.post_deleted_successfully'));
    }

    /**
     * Delete images found in post content HTML.
     * Scans for image URLs and deletes corresponding files from the filesystem.
     */
    private function deleteImagesFromContent($content)
    {
        // Find all image URLs in the content
        preg_match_all('/src=["\']([^"\']*images\/posts\/[^"\']*)["\']/', $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $imageUrl) {
                // Extract filename from URL
                $filename = basename($imageUrl);
                $imagePath = public_path("images/posts/{$filename}");
                
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
        }
    }

    /**
     * Create a safe, unique slug from the post title, handling special characters.
     * Uses transliteration or timestamp fallback if needed.
     */
    private function createSafeSlug($title, $postId = null)
    {
        // Thử tạo slug từ title
        $slug = Str::slug($title);

        // Nếu slug rỗng (ví dụ với ký tự Nhật, Trung, Hàn)
        if (empty($slug)) {
            // Sử dụng transliteration hoặc fallback
            $slug = $this->transliterateToSlug($title);
        }

        // Nếu vẫn rỗng, dùng timestamp
        if (empty($slug)) {
            $slug = 'post-' . time();
        }

        // Đảm bảo slug unique
        return $this->ensureUniqueSlug($slug, $postId);
    }

    /**
     * Transliterate special characters to create a slug.
     * Removes non-letter/number characters and generates a fallback slug if needed.
     */
    private function transliterateToSlug($text)
    {
        // Loại bỏ ký tự đặc biệt và giữ lại chữ cái, số
        $slug = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $text);
        
        // Nếu sau khi loại bỏ ký tự đặc biệt vẫn có nội dung
        if (!empty(trim($slug))) {
            return Str::slug($slug);
        }

        // Nếu không có ký tự nào còn lại, tạo slug từ độ dài title
        return 'post-' . strlen($text) . '-' . substr(md5($text), 0, 8);
    }

    /**
     * Ensure the generated slug is unique in the posts table.
     * Appends a counter if a duplicate is found.
     */
    private function ensureUniqueSlug($slug, $postId = null)
    {
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Post::where('slug', $slug);
            
            // Nếu đang update, loại trừ post hiện tại
            if ($postId) {
                $query->where('id', '!=', $postId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

}
