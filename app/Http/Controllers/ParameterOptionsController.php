<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParameterOption;

class ParameterOptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parameterOptions = ParameterOption::get();
        return response()->json($parameterOptions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ParameterOption::create($request->input());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $parameterOptions = ParameterOption::find($id);
        return response()->json($parameterOptions);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $parameterOptions = ParameterOption::find($id);
        $parameterOptions->parameter_id = $request->parameter_id;
        $parameterOptions->option_value = $request->option_value;
        $parameterOptions->option_label = $request->option_label;
        $parameterOptions->save();
        return response()->json($parameterOptions);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ParameterOption::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
