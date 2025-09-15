<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Projects\Category;
use Illuminate\Http\Request;
use App\Trait\ImagePathTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    use ImagePathTrait;

    public function index()
    {
        try {
            $categories = Category::all();

            if ($categories->isEmpty()) {
                return response()->json([
                    'message' => 'No categories found.',
                    'categories' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Success',
                'categories' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()]);
        }
    }

    public function show(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:categories,id'
        ], [
            'id.required' => 'Category id cannot be empty.',
            'id.exists' => 'Category id does not exist.',
        ]);
        try {
            $category = Category::whereId($request->id)->with('projects')->first();
            return response()->json([
                'message' => 'Success',
                'category' => $category]);


        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {
            $category = Category::create([
                'name' => $request->name,
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $this->imagePath($request->file('image'), 'category', $category);
                $category->update(['image' => $imagePath]);
            }

            return response()->json([
                'status' => 'success',
                'data' => $category,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:categories,id',
            'name' => 'nullable|string|unique:categories,name,' . $request->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {
            $category = Category::findOrFail($request->id);
            $newdata['name'] = $request->name ?? $category->name;
            if ($request->hasFile('image')) {
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }

                $imagePath = $this->imagePath($request->file('image'), 'category', $category);
                $newdata['image'] = $imagePath;
            } else {
                $newdata['image'] = $category->image;
            }


            $category->update($newdata);

            return response()->json([
                'status' => 'success',
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:categories,id',
        ]);
        try {
            $category = Category::whereId($request->id)->first();
            Storage::disk('public')->deleteDirectory('categories/' . $category->id);

            $category->delete();
            return response()->json([
                'status' => 'category deleted',

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}

