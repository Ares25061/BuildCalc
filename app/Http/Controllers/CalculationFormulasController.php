<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalculationFormula;
class CalculationFormulasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $calculationFormulas = CalculationFormula::get();
            return response()->json($calculationFormulas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        CalculationFormula::create($request->input());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $calculationFormulas = CalculationFormula::Find($id);
          return response()->json($calculationFormulas);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $calculationFormulas = CalculationFormula::find($id);
        $calculationFormulas->formula_name = $request->formula_name;
        $calculationFormulas->formula_expression = $request->formula_expression;
        $calculationFormulas->description = $request->description;
        $calculationFormulas->is_default= $request->is_default;
        $calculationFormulas->save();
        return response()->json($calculationFormulas);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CalculationFormula::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
