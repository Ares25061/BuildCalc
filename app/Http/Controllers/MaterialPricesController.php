<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialPrice;

class MaterialPricesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materialPrices = MaterialPrice::get();
        return response()->json($materialPrices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        MaterialPrice::create($request->input());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $materialPrice = MaterialPrice::find($id);
        return response()->json($materialPrice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $materialPrice = MaterialPrice::find($id);
        $materialPrice->material_id = $request->material_id;
        $materialPrice->price = $request->price;
        $materialPrice->price_date = $request->price_date;
        $materialPrice->url = $request->url;
        $materialPrice->created_at = $request->created_at;
        $materialPrice->save();
        return response()->json($materialPrice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        MaterialPrice::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
