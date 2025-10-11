<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class MaterialsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = Material::get();
        return response()->json($materials);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Material::create($request->input());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $material = Material::find($id);
        return response()->json($material);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $material = Material::find($id);
        $material->name = $request->name;
        $material->category_id = $request->category_id;
        $material->description = $request->description;
        $material->unit = $request->unit;
        $material->article = $request->article;
        $material->image_url = $request->image_url;
        $material->created_at = $request->created_at;
        $material->updated_at = $request->updated_at;
        $material->supplier_id = $request->supplier_id;
        $material->external_id = $request->external_id;
        $material->save();
        return response()->json($material);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Material::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
