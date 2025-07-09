<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Category; // Assuming you have a Category model
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CategoriesController extends Controller
{
    //
    public function index()
    {
        $categories = Cache::remember('admin_categories_all', 600, function () {
            return Category::all();
        });
        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        // Return the view for creating a new category
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // Validate and store the category
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $generatedFileName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $generatedFileName = $imageName;
        } else {
            $generatedFileName = 'base.jpg';
        }

        $category = new Category();
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->image_path = $generatedFileName;
        $category->save();
        Cache::forget('admin_categories_all'); // Xóa cache khi thêm mới
        return redirect()->route('admin.category')->with('success', 'Category created successfully!');
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate and update the category
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        if ($request->hasFile('image')) {

            if ($category->image_path && file_exists(public_path('images/' . $category->image_path))) {
                unlink(public_path('images/' . $category->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $category->image_path = $imageName;
        }
        $category->save();
        Cache::forget('admin_categories_all'); // Xóa cache khi cập nhật
        return redirect()->route('admin.category')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        // Find the category by ID and delete it
        $category = Category::findOrFail($id);
        $imagePath = public_path('images/' . $category->image_path);

        if ($category->image_path && file_exists($imagePath)) {
            if (unlink($imagePath)) {
                Log::info("Xóa ảnh thành công");
            } else {
                Log::error("Xóa ảnh thất bại");
            }
        }
        $category->delete();
        Cache::forget('admin_categories_all'); // Xóa cache khi xóa
        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $categories = Category::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($categories);
    }

    public function show($id, Request $request)
    {
        $category = Category::findOrFail($id);

        // Kiểm tra nhiều điều kiện để xác định AJAX request
        $isAjax = $request->ajax() ||
            $request->wantsJson() ||
            $request->expectsJson() ||
            $request->header('X-Requested-With') === 'XMLHttpRequest' ||
            strpos($request->header('Accept', ''), 'application/json') !== false ||
            $request->query('ajax') === '1'; // Thêm parameter để force JSON

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description ?? '',
                'image_path' => asset('images/' . ($category->image_path ?? 'base.jpg')),
            ]);
        }

        // Nếu không, trả về view chi tiết
        return view('admin.categories.show', [
            'category' => $category,
        ]);
    }
}
