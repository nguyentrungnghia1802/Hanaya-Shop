<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CategoriesController extends Controller
{
    /**
     * Display a list of all categories.
     * Data is cached for 60 minutes to reduce database load.
     */
    public function index()
    {
        $categories = Cache::remember('admin_categories_all', 3600, function () {
            return Category::all();
        });

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in the database.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $generatedFileName = null;

        // Handle image upload if available
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); // Unique filename with timestamp
            $image->move(public_path('images'), $imageName);
            $generatedFileName = $imageName;
        } else {
            // Default image if none is uploaded
            $generatedFileName = 'base.jpg';
        }

        // Create and save the category
        $category = new Category();
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->image_path = $generatedFileName;
        $category->save();

        // Clear cache to refresh data
        Cache::forget('admin_categories_all');

        return redirect()->route('admin.category')->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified category in the database.
     */
    public function update(Request $request, $id)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        // Handle image upload and replace old image if needed
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($category->image_path && file_exists(public_path('images/' . $category->image_path))) {
                unlink(public_path('images/' . $category->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $category->image_path = $imageName;
        }

        $category->save();

        // Clear cache to refresh data
        Cache::forget('admin_categories_all');

        return redirect()->route('admin.category')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from the database.
     * Also deletes the associated image file if it exists.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $imagePath = public_path('images/' . $category->image_path);

        // Delete image file if exists
        if ($category->image_path && file_exists($imagePath)) {
            if (unlink($imagePath)) {
                Log::info("Image deleted successfully.");
            } else {
                Log::error("Failed to delete image.");
            }
        }

        $category->delete();

        // Clear cache to refresh data
        Cache::forget('admin_categories_all');

        return response()->json(['success' => true]);
    }

    /**
     * Search for categories by name or description.
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');

        $categories = Category::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        $html = view('admin.categories.partials.table_rows', compact('categories'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Display the specified category details.
     * If request is AJAX/JSON, return JSON response.
     */
    public function show($id, Request $request)
    {
        $category = Category::findOrFail($id);

        // Determine if request is AJAX or expects JSON
        $isAjax = $request->ajax() ||
            $request->wantsJson() ||
            $request->expectsJson() ||
            $request->header('X-Requested-With') === 'XMLHttpRequest' ||
            strpos($request->header('Accept', ''), 'application/json') !== false ||
            $request->query('ajax') === '1'; // Additional manual flag

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description ?? '',
                'image_path' => asset('images/' . ($category->image_path ?? 'base.jpg')),
            ]);
        }

        // Return view if not an AJAX/JSON request
        return view('admin.categories.show', [
            'category' => $category,
        ]);
    }
}
