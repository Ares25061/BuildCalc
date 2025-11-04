<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Material;
use App\Models\ProjectItem;
use App\Models\SelectedProjectMaterial;
use App\Models\WorkType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Получаем проекты только для текущего пользователя
        $projects = Project::where('user_id', Auth::id())->get();
        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:draft,in_progress,completed',
            'total_estimated_cost' => 'nullable|numeric|min:0'
        ]);

        try {
            $project = Project::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status ?? 'draft',
                'total_estimated_cost' => $request->total_estimated_cost ?? 0,
            ]);

            return response()->json($project, 201);

        } catch (\Exception $e) {
            Log::error('Error creating project: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при создании сметы',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::where('user_id', Auth::id())->find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::where('user_id', Auth::id())->find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:draft,in_progress,completed',
            'total_estimated_cost' => 'nullable|numeric'
        ]);

        try {
            $project->update($request->only(['name', 'description', 'status', 'total_estimated_cost']));

            return response()->json($project);

        } catch (\Exception $e) {
            Log::error('Error updating project: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при обновлении сметы',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::where('user_id', Auth::id())->find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            $project->delete();
            return response()->json(['message' => 'Project deleted']);

        } catch (\Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при удалении сметы',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project materials
     */
    public function getMaterials($projectId)
    {
        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            // Получаем материалы проекта через project_items
            $materials = SelectedProjectMaterial::whereHas('projectItem', function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
                ->with(['material', 'material.prices', 'material.supplier'])
                ->get()
                ->map(function($selectedMaterial) {
                    $material = $selectedMaterial->material;
                    $latestPrice = $material->prices->last();

                    return [
                        'id' => $selectedMaterial->id,
                        'material_id' => $material->id,
                        'name' => $material->name,
                        'description' => $material->description,
                        'unit' => $material->unit,
                        'quantity' => (float) $selectedMaterial->quantity,
                        'price' => $latestPrice ? (float) $latestPrice->price : 0,
                        'total' => $latestPrice ? (float) $latestPrice->price * (float) $selectedMaterial->quantity : 0,
                        'brand' => $material->brand,
                        'color' => $material->color,
                        'supplier' => $material->supplier,
                        'created_at' => $selectedMaterial->created_at,
                        'updated_at' => $selectedMaterial->updated_at
                    ];
                });

            return response()->json($materials);

        } catch (\Exception $e) {
            Log::error('Error getting project materials: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при получении материалов проекта',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Add material to project
     */
    public function addMaterial(Request $request, $projectId)
    {
        Log::info('=== START addMaterial ===', [
            'project_id' => $projectId,
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        try {
            // Проверяем проект
            $project = Project::where('user_id', Auth::id())->find($projectId);
            if (!$project) {
                Log::warning('Project not found', ['project_id' => $projectId]);
                return response()->json(['message' => 'Project not found'], 404);
            }

            // Валидация
            $request->validate([
                'material_id' => 'required|exists:materials,id',
                'quantity' => 'required|numeric|min:0.001'
            ]);

            Log::info('Validation passed');

            // Проверяем существование work_type с id=1
            $workType = WorkType::find(1);
            if (!$workType) {
                Log::info('Work type 1 not found, creating...');
                $workType = WorkType::create([
                    'id' => 1,
                    'name' => 'Материалы',
                    'description' => 'Основные материалы проекта',
                    'unit' => 'шт',
                    'created_at' => now()
                ]);
                Log::info('Work type created', ['id' => $workType->id]);
            }

            // Ищем или создаем project_item БЕЗ updated_at
            $projectItem = ProjectItem::where('project_id', $projectId)
                ->where('work_type_id', 1)
                ->first();

            if (!$projectItem) {
                Log::info('Creating new project item');
                $projectItem = ProjectItem::create([
                    'project_id' => $projectId,
                    'work_type_id' => 1,
                    'quantity' => 1,
                    'notes' => 'Материалы проекта',
                    'sort_order' => 0,
                    'created_at' => now()
                ]);
                Log::info('Project item created', ['id' => $projectItem->id]);
            }

            // Проверяем, есть ли уже такой материал
            $existingMaterial = SelectedProjectMaterial::where('project_item_id', $projectItem->id)
                ->where('material_id', $request->material_id)
                ->first();

            if ($existingMaterial) {
                // Обновляем количество
                $newQuantity = $existingMaterial->quantity + $request->quantity;
                $existingMaterial->update(['quantity' => $newQuantity]);

                Log::info('Material quantity updated', [
                    'material_id' => $request->material_id,
                    'old_quantity' => $existingMaterial->quantity,
                    'new_quantity' => $newQuantity
                ]);

                return response()->json([
                    'message' => 'Material quantity updated in project',
                    'data' => $existingMaterial
                ]);
            }

            // Создаем новую запись
            Log::info('Creating new selected project material');
            $selectedMaterial = SelectedProjectMaterial::create([
                'project_item_id' => $projectItem->id,
                'material_id' => $request->material_id,
                'quantity' => $request->quantity,
                'created_at' => now()
            ]);

            Log::info('Material added successfully', [
                'selected_material_id' => $selectedMaterial->id,
                'project_item_id' => $projectItem->id,
                'material_id' => $request->material_id
            ]);

            return response()->json([
                'message' => 'Material added to project',
                'data' => [
                    'id' => $selectedMaterial->id,
                    'material_id' => $selectedMaterial->material_id,
                    'quantity' => $selectedMaterial->quantity
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('FINAL ERROR in addMaterial: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'message' => 'Error adding material to project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update project material quantity
     */
    public function updateMaterial(Request $request, $projectId, $materialId)
    {
        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            $selectedMaterial = SelectedProjectMaterial::where('id', $materialId)
                ->whereHas('projectItem', function($query) use ($projectId) {
                    $query->where('project_id', $projectId);
                })
                ->first();

            if (!$selectedMaterial) {
                return response()->json(['message' => 'Material not found in project'], 404);
            }

            $request->validate([
                'quantity' => 'required|numeric|min:0.001'
            ]);

            $selectedMaterial->update([
                'quantity' => $request->quantity
            ]);

            // Обновляем общую стоимость проекта
            $this->updateProjectTotalCost($projectId);

            $selectedMaterial->load(['material', 'material.prices', 'material.supplier']);

            return response()->json([
                'message' => 'Material updated',
                'data' => $selectedMaterial
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating project material: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при обновлении материала',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove material from project
     */
    public function removeMaterial($projectId, $materialId)
    {
        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            $selectedMaterial = SelectedProjectMaterial::where('id', $materialId)
                ->whereHas('projectItem', function($query) use ($projectId) {
                    $query->where('project_id', $projectId);
                })
                ->first();

            if (!$selectedMaterial) {
                return response()->json(['message' => 'Material not found in project'], 404);
            }

            $selectedMaterial->delete();

            // Обновляем общую стоимость проекта
            $this->updateProjectTotalCost($projectId);

            return response()->json(['message' => 'Material removed from project']);

        } catch (\Exception $e) {
            Log::error('Error removing project material: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при удалении материала',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update project total estimated cost
     */
    private function updateProjectTotalCost($projectId)
    {
        try {
            $totalCost = SelectedProjectMaterial::whereHas('projectItem', function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
                ->with(['material.prices'])
                ->get()
                ->reduce(function ($carry, $selectedMaterial) {
                    $material = $selectedMaterial->material;
                    $latestPrice = $material->prices->last();
                    $price = $latestPrice ? (float) $latestPrice->price : 0;
                    return $carry + ($price * (float) $selectedMaterial->quantity);
                }, 0);

            Project::where('id', $projectId)->update([
                'total_estimated_cost' => $totalCost
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating project total cost: ' . $e->getMessage());
        }
    }

    /**
     * Get project with full details including materials
     */
    public function getProjectWithDetails($projectId)
    {
        $project = Project::where('user_id', Auth::id())->with(['projectItems.selectedMaterials.material.prices'])->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project);
    }

    /**
     * Calculate project budget summary
     */
    public function getBudgetSummary($projectId)
    {
        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            $materials = SelectedProjectMaterial::whereHas('projectItem', function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
                ->with(['material.prices'])
                ->get();

            $materialsTotal = $materials->reduce(function ($carry, $selectedMaterial) {
                $material = $selectedMaterial->material;
                $latestPrice = $material->prices->last();
                $price = $latestPrice ? (float) $latestPrice->price : 0;
                return $carry + ($price * (float) $selectedMaterial->quantity);
            }, 0);

            $materialsCount = $materials->count();
            $projectBudget = $project->total_estimated_cost ?? 0;
            $savings = $projectBudget - $materialsTotal;

            return response()->json([
                'materials_total' => $materialsTotal,
                'materials_count' => $materialsCount,
                'project_budget' => $projectBudget,
                'savings' => $savings,
                'is_within_budget' => $savings >= 0
            ]);

        } catch (\Exception $e) {
            Log::error('Error calculating budget summary: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при расчете бюджета',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
