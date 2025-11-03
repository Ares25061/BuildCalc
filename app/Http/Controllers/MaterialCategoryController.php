<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialCategory;
use App\Models\Material;

class MaterialCategoryController extends Controller
{
    /**
     * Display categories for web page
     */
    public function indexWeb()
    {
        // Получаем только дочерние категории (исключаем родительскую "Стройматериалы OBI")
        $categories = MaterialCategory::whereNotNull('parent_id')
            ->withCount('materials')
            ->orderBy('name')
            ->get();

        return view('categories', compact('categories'));
    }

    /**
     * Display materials for specific category
     */
    public function showCategoryMaterials($slug)
    {
        // Заменяем нижние подчёркивания обратно на пробелы
        $categoryName = str_replace('_', ' ', $slug);

        // Ищем категорию по slug или имени
        $category = MaterialCategory::where('slug', $slug)
            ->orWhere('name', $categoryName)
            ->firstOrFail();

        $materials = $category->materials()->with(['prices', 'supplier'])->get();

        // Получаем все категории для навигации (если нужно в боковом меню)
        $categories = MaterialCategory::whereNotNull('parent_id')
            ->withCount('materials')
            ->orderBy('name')
            ->get();

        return view('materials', compact('category', 'materials', 'categories'));
    }

    /**
     * Display a listing of the resource for API
     */
    public function index()
    {
        $categories = MaterialCategory::get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        MaterialCategory::create($request->input());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = MaterialCategory::find($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = MaterialCategory::find($id);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->image_url = $request->image_url;
        $category->created_at = $request->created_at;
        $category->save();
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        MaterialCategory::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
