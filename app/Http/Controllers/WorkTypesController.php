<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkType;

class WorkTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workTypes = WorkType::get();
        return response()->json($workTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        WorkType::create($request->input());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $workType = WorkType::find($id);
        return response()->json($workType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $workType = WorkType::find($id);
        $workType->name = $request->name;
        $workType->description = $request->description;
        $workType->unit = $request->unit;
        $workType->created_at = $request->created_at;
        $workType->save();
        return response()->json($workType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        WorkType::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
