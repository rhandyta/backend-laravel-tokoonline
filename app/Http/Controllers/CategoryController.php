<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return $categories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_category' => 'required|min:2|string|unique:categories'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 401);
        }
        $newCategory = Category::create([
            'name_category' => $request->name_category,
            'slug' => \Str::slug($request->name_category),
        ]);
        if ($newCategory) {
            return response()->json([
                'success' => true,
                'message' => 'added category successfully',
                'data' => $newCategory
            ], 201);
        }
        return response()->json(['success' => false, 'message' => 'failed added category'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        if (!$category) {
            return response()->json([
                'success' => false,
                'data' => 'category not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name_category' => 'required|min:2|string|unique:categories'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 401);
        }
        $category = Category::findOrFail($category->id);
        $resultUpdate = $category->update([
            'name_category' => $request->name_category,
            'slug' => \Str::slug($request->name_category),
        ]);
        if ($resultUpdate) {
            return response()->json([
                'success' => true,
                'message' => 'updated category successfully',
                'data' => $category
            ], 201);
        }
        return response()->json(['success' => false, 'message' => 'failed added category'], 409);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (!$category) {
            return response()->json([
                'success' => false,
                'data' => 'category not found',
            ]);
        }
        $result = $category->delete();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'category has been deleted'
            ]);
        }
    }
}
