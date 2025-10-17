<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialConsumptionRate;

class MaterialConsumptionRatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = MaterialConsumptionRate::get();
        return response()->json($rates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        MaterialConsumptionRate::create($request->input());
        return response()->json(['message' => 'Created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rate = MaterialConsumptionRate::find($id);
        return response()->json($rate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rate = MaterialConsumptionRate::find($id);
        $rate->work_type_id = $request->work_type_id;
        $rate->material_id = $request->material_id;
        $rate->consumption_rate = $request->consumption_rate;
        $rate->created_at = $request->created_at;
        $rate->save();
        return response()->json($rate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        MaterialConsumptionRate::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
