<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\MaterialCategory;

class MaterialsController extends Controller
{
    /**
     * Display materials page for specific category
     */
    public function showCategoryMaterials($slug)
    {
        $categoryName = str_replace('_', ' ', $slug);
        $category = MaterialCategory::where('slug', $slug)
            ->orWhere('name', $categoryName)
            ->firstOrFail();
        $materials = Material::with(['category', 'prices', 'supplier'])
            ->where('category_id', $category->id)
            ->get();
        $categories = MaterialCategory::whereNotNull('parent_id')->get();

        return view('materials', compact('materials', 'categories', 'category'));
    }

    /**
     * Display a listing of the resource (API).
     */
    public function index(Request $request)
    {
        $query = Material::with(['category', 'prices', 'supplier']);
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $materials = $query->get();

        return response()->json($materials);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $material = Material::create($request->all());
        return response()->json($material, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $material = Material::with(['category', 'prices', 'supplier'])->find($id);

        if (!$material) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        return response()->json($material);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $material = Material::find($id);

        if (!$material) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        $material->update($request->all());
        return response()->json($material);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $material = Material::find($id);

        if (!$material) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        $material->delete();
        return response()->json(['message' => 'Material deleted']);
    }
}
