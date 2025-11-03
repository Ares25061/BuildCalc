<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\MaterialCategory;

class MaterialsController extends Controller
{
    /**
     * Display materials page for web
     */
    public function indexWeb(Request $request)
    {
        $categoryName = $request->get('category', 'Кирпич и блоки');

        // Get materials with relationships
        $materials = Material::with(['category', 'latestPrice', 'supplier'])
            ->whereHas('category', function($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })
            ->get();

        $categories = MaterialCategory::whereNotNull('parent_id')->get();

        return view('materials.index', compact('materials', 'categories', 'categoryName'));
    }

    /**
     * Display a listing of the resource (API).
     */
    public function index()
    {
        $materials = Material::with(['category', 'latestPrice'])->get();
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
        $material = Material::with(['category', 'latestPrice', 'supplier'])->find($id);

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
