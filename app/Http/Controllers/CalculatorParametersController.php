<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalculatorParameter;

class CalculatorParametersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $calculatorParameters = CalculatorParameter::get();
        return response()->json($calculatorParameters);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        CalculatorParameter::create($request->input());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $calculatorParameters = CalculatorParameter::find($id);
        return response()->json($calculatorParameters);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $calculatorParameters = CalculatorParameter::find($id);
        $calculatorParameters->category_id = $request->category_id;
        $calculatorParameters->parameter_name = $request->parameter_name;
        $calculatorParameters->display_name = $request->display_name;
        $calculatorParameters->parameter_type = $request->parameter_type;
        $calculatorParameters->save();
        return response()->json($calculatorParameters);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CalculatorParameter::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
