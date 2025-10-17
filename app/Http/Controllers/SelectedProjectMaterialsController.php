<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SelectedProjectMaterial;

class SelectedProjectMaterialsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = SelectedProjectMaterial::get();
        return response()->json($materials);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        SelectedProjectMaterial::create($request->input());
        return response()->json(['message' => 'Created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $material = SelectedProjectMaterial::find($id);
        return response()->json($material);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $material = SelectedProjectMaterial::find($id);
        $material->project_item_id = $request->project_item_id;
        $material->material_id = $request->material_id;
        $material->quantity = $request->quantity;
        $material->created_at = $request->created_at;
        $material->save();
        return response()->json($material);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SelectedProjectMaterial::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
