<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{
    /**
     * Display a list of all categories.
     *
     * Shows a paginated list of all product categories for administrators.
     * Data is not cached for pagination. Each page displays 20 categories.
     *
     * @return \Illuminate\View\View Category list view
     */
    public function index()
    {
        // Không dùng cache cho phân trang
        $categories = Category::paginate(20); // 20 category mỗi trang

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     *
     * Displays the form for administrators to create a new product category.
     *
     * @return \Illuminate\View\View Category creation form view
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in the database.
     *
     * Handles the creation of a new product category, including validation,
     * image upload, and saving to the database. Clears category cache after creation.
     *
     * @param  Request  $request  HTTP request with category data
     * @return \Illuminate\Http\RedirectResponse Redirect to category list with success message
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
            $imageName = time().'.'.$image->getClientOriginalExtension(); // Unique filename with timestamp
            $image->move(public_path('images/categories'), $imageName);
            $generatedFileName = $imageName;
        } else {
            // Default image if none is uploaded
            $generatedFileName = 'fixed_resources/not_found.jpg'; // Use a default image path
        }

        // Create and save the category
        $category = new Category;
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->image_path = $generatedFileName;
        $category->save();

        // Clear cache to refresh data
        Cache::forget('admin_categories_all');

        return redirect()->route('admin.category')->with('success', __('admin.category_created_successfully'));
    }

    /**
     * Show the form for editing the specified category.
     *
     * Displays the form for editing an existing product category.
     *
     * @param  int  $id  Category ID
     * @return \Illuminate\View\View Category edit form view
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
     *
     * Handles updating an existing product category, including validation,
     * image upload/replacement, and saving changes. Clears category cache after update.
     *
     * @param  Request  $request  HTTP request with updated category data
     * @param  int  $id  Category ID
     * @return \Illuminate\Http\RedirectResponse Redirect to category list with success message
     */
    public function update(Request $request, $id)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        // Handle image upload and replace old image if needed
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($category->image_path && file_exists(public_path('images/categories/'.$category->image_path))) {
                unlink(public_path('images/categories/'.$category->image_path));
            }

            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images/categories'), $imageName);
            $category->image_path = $imageName;
        }

        $category->save();

        // Clear cache to refresh data
        Cache::forget('admin_categories_all');

        return redirect()->route('admin.category')->with('success', __('admin.category_updated_successfully'));
    }

    /**
     * Remove the specified category from the database.
     *
     * Deletes a product category and its associated image file if it exists.
     * Clears category cache after deletion. Returns JSON response for AJAX requests.
     *
     * @param  int  $id  Category ID
     * @return \Illuminate\Http\JsonResponse JSON response indicating success
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $imagePath = public_path('images/categories/'.$category->image_path);

        // Delete image file if exists
        if ($category->image_path && file_exists($imagePath)) {
            if (unlink($imagePath)) {
                Log::info(__('admin.image_deleted_successfully'));
            } else {
                Log::error(__('admin.failed_to_delete_image'));
            }
        }

        $category->delete();

        // Clear cache to refresh data
        Cache::forget('admin_categories_all');

        return response()->json(['success' => true]);
    }

    /**
     * Search for categories by name or description.
     *
     * Searches product categories by name or description and returns HTML table rows
     * for display in the admin interface. Used for AJAX search functionality.
     *
     * @param  Request  $request  HTTP request with search query
     * @return \Illuminate\Http\JsonResponse JSON response with HTML table rows
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
     *
     * Shows details for a specific product category. Returns JSON response for AJAX/JSON
     * requests, or renders the category details view for standard requests.
     *
     * @param  int  $id  Category ID
     * @param  Request  $request  HTTP request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Category details view or JSON response
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
                'image_path' => asset('images/categories/'.($category->image_path ?? 'fixed_resources/not_found.jpg')),
            ]);
        }

        // Return view if not an AJAX/JSON request
        return view('admin.categories.show', [
            'category' => $category,
        ]);
    }
}
