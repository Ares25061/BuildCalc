<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectItem;

class ProjectItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectItems = ProjectItem::get();
        return response()->json($projectItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ProjectItem::create($request->input());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $projectItem = ProjectItem::find($id);
        return response()->json($projectItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $projectItem = ProjectItem::find($id);
        $projectItem->project_id = $request->project_id;
        $projectItem->work_type_id = $request->work_type_id;
        $projectItem->quantity = $request->quantity;
        $projectItem->notes = $request->notes;
        $projectItem->sort_order = $request->sort_order;
        $projectItem->created_at = $request->created_at;
        $projectItem->save();
        return response()->json($projectItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ProjectItem::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
