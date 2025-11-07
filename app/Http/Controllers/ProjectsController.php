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
    public function index(Request $request)
    {
        try {
            $query = Project::where('user_id', Auth::id());
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'ILIKE', $searchTerm)
                        ->orWhere('description', 'ILIKE', $searchTerm);
                });
            }
            $sortField = 'updated_at';
            $sortDirection = 'DESC';

            if ($request->has('sort')) {
                switch ($request->sort) {
                    case 'date_asc':
                        $sortField = 'updated_at';
                        $sortDirection = 'ASC';
                        break;
                    case 'date_desc':
                        $sortField = 'updated_at';
                        $sortDirection = 'DESC';
                        break;
                    case 'cost_asc':
                        $sortField = 'total_estimated_cost';
                        $sortDirection = 'ASC';
                        break;
                    case 'cost_desc':
                        $sortField = 'total_estimated_cost';
                        $sortDirection = 'DESC';
                        break;
                    case 'name_asc':
                        $sortField = 'name';
                        $sortDirection = 'ASC';
                        break;
                    case 'name_desc':
                        $sortField = 'name';
                        $sortDirection = 'DESC';
                        break;
                }
            }

            $query->orderBy($sortField, $sortDirection);

            $projects = $query->get();

            return response()->json($projects);

        } catch (\Exception $e) {
            Log::error('Error loading projects: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при загрузке смет',
                'error' => $e->getMessage()
            ], 500);
        }
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
     * Get project items with materials
     */
    public function getProjectItems($projectId)
    {
        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            $projectItems = ProjectItem::where('project_id', $projectId)
                ->with(['workType', 'selectedMaterials.material.prices', 'selectedMaterials.material.supplier'])
                ->orderBy('sort_order')
                ->get()
                ->map(function($item) {
                    $materials = $item->selectedMaterials->map(function($selectedMaterial) {
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
                        ];
                    });

                    $itemTotal = $materials->sum('total');

                    return [
                        'id' => $item->id,
                        'work_type_id' => $item->work_type_id,
                        'work_type_name' => $item->workType->name,
                        'work_type_unit' => $item->workType->unit,
                        'notes' => $item->notes,
                        'sort_order' => $item->sort_order,
                        'materials' => $materials,
                        'item_total' => $itemTotal,
                        'materials_count' => $materials->count(),
                        'created_at' => $item->created_at,
                    ];
                });

            return response()->json($projectItems);

        } catch (\Exception $e) {
            Log::error('Error getting project items: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при получении позиций проекта',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add work position to project
     */
    public function addWorkPosition(Request $request, $projectId)
    {
        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $request->validate([
            'work_type_id' => 'required|exists:work_types,id',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $maxSortOrder = ProjectItem::where('project_id', $projectId)->max('sort_order') ?? 0;
            $projectItem = ProjectItem::create([
                'project_id' => $projectId,
                'work_type_id' => $request->work_type_id,
                'quantity' => 1,
                'notes' => $request->notes ?? 'Новая позиция работ',
                'sort_order' => $maxSortOrder + 1,
                'created_at' => now()
            ]);
            $projectItem->load('workType');

            return response()->json([
                'message' => 'Work position added successfully',
                'data' => $projectItem
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error adding work position: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при добавлении позиции работ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update work position
     */
    public function updateWorkPosition(Request $request, $projectId, $itemId)
    {
        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $projectItem = ProjectItem::where('id', $itemId)
            ->where('project_id', $projectId)
            ->first();

        if (!$projectItem) {
            return response()->json(['message' => 'Work position not found'], 404);
        }

        $request->validate([
            'work_type_id' => 'sometimes|exists:work_types,id',
            'notes' => 'nullable|string|max:500',
            'sort_order' => 'sometimes|integer',
        ]);

        try {
            $projectItem->update($request->only(['work_type_id', 'notes', 'sort_order']));

            $projectItem->load('workType');

            return response()->json([
                'message' => 'Work position updated successfully',
                'data' => $projectItem
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating work position: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при обновлении позиции работ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete work position
     */
    public function deleteWorkPosition($projectId, $itemId)
    {
        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $projectItem = ProjectItem::where('id', $itemId)
            ->where('project_id', $projectId)
            ->first();

        if (!$projectItem) {
            return response()->json(['message' => 'Work position not found'], 404);
        }

        try {
            SelectedProjectMaterial::where('project_item_id', $itemId)->delete();
            $projectItem->delete();
            return response()->json(['message' => 'Work position deleted successfully']);

        } catch (\Exception $e) {
            Log::error('Error deleting work position: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при удалении позиции работ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder work positions
     */
    public function reorderWorkPositions(Request $request, $projectId)
    {
        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:project_items,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        try {
            foreach ($request->items as $item) {
                ProjectItem::where('id', $item['id'])
                    ->where('project_id', $projectId)
                    ->update(['sort_order' => $item['sort_order']]);
            }

            return response()->json(['message' => 'Work positions reordered successfully']);

        } catch (\Exception $e) {
            Log::error('Error reordering work positions: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ошибка при изменении порядка позиций',
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

        $project = Project::where('user_id', Auth::id())->find($projectId);

        if (!$project) {
            Log::warning('Project not found', ['project_id' => $projectId]);
            return response()->json(['message' => 'Project not found'], 404);
        }

        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|numeric|min:0.001',
            'project_item_id' => 'nullable|exists:project_items,id'
        ]);

        try {
            $projectItemId = $request->project_item_id;

            if (!$projectItemId) {
                Log::info('No project_item_id provided, using default logic');
                $workType = WorkType::firstOrCreate(
                    ['id' => 1],
                    [
                        'name' => 'Материалы',
                        'description' => 'Основные материалы проекта',
                        'unit' => 'шт',
                        'created_at' => now()
                    ]
                );
                $projectItem = ProjectItem::firstOrCreate([
                    'project_id' => $projectId,
                    'work_type_id' => $workType->id,
                ], [
                    'quantity' => 1,
                    'notes' => 'Материалы проекта',
                    'sort_order' => 0,
                    'created_at' => now()
                ]);

                $projectItemId = $projectItem->id;
                Log::info('Using default project item', ['project_item_id' => $projectItemId]);
            } else {
                $projectItem = ProjectItem::where('id', $projectItemId)
                    ->where('project_id', $projectId)
                    ->first();

                if (!$projectItem) {
                    return response()->json(['message' => 'Work position not found in project'], 404);
                }
                Log::info('Using specified project item', ['project_item_id' => $projectItemId]);
            }
            $existingMaterial = SelectedProjectMaterial::where('project_item_id', $projectItemId)
                ->where('material_id', $request->material_id)
                ->first();

            if ($existingMaterial) {
                $existingMaterial->update([
                    'quantity' => $existingMaterial->quantity + $request->quantity
                ]);
                $existingMaterial->load(['material', 'material.prices', 'material.supplier']);
                Log::info('Material quantity updated', [
                    'material_id' => $request->material_id,
                    'project_item_id' => $projectItemId,
                    'new_quantity' => $existingMaterial->quantity
                ]);
                return response()->json([
                    'message' => 'Material quantity updated in project',
                    'data' => $existingMaterial
                ]);
            }
            $selectedMaterial = SelectedProjectMaterial::create([
                'project_item_id' => $projectItemId,
                'material_id' => $request->material_id,
                'quantity' => $request->quantity,
                'created_at' => now()
            ]);
            Log::info('New material added to specific position', [
                'selected_material_id' => $selectedMaterial->id,
                'material_id' => $request->material_id,
                'project_item_id' => $projectItemId
            ]);
            $selectedMaterial->load(['material', 'material.prices', 'material.supplier']);
            $this->updateProjectTotalCost($projectId);
            return response()->json([
                'message' => 'Material added to project',
                'data' => $selectedMaterial
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error adding material to project: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Error adding material to project',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
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
