<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialCategory;

class MaterialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MaterialCategory::get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        MaterialCategory::create($request->input());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = MaterialCategory::find($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = MaterialCategory::find($id);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->created_at = $request->created_at;
        $category->save();
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        MaterialCategory::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
