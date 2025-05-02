<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category; // Assuming you have a Category model

class CategoriesController extends Controller
{
    //
    public function index()
    {
        $categories = Category::all(); // Fetch all categories
        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        // Validate and store the category
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = new Category();
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->save();

        return response()->json($category);
       
    }

    public function update(Request $request, $id)
    {
        // Validate and update the category
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->save();

        return response()->json($category);
    }
    
    public function destroy($id)
    {
        // Find the category by ID and delete it
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true]);
    }
}
