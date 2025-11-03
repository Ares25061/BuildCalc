<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\Supplier;
use App\Models\MaterialPrice;
use Illuminate\Support\Facades\DB;

class MaterialsSeeder extends Seeder
{

    // test data w/o parser
    public function run(): void
    {
        $bricksCategory = MaterialCategory::where('name', 'Кирпич и блоки')->first();
        $paintCategory = MaterialCategory::where('name', 'Краски')->first();
        $tilesCategory = MaterialCategory::where('name', 'Плитка')->first();

        if (!$bricksCategory) {
            $bricksCategory = MaterialCategory::create([
                'name' => 'Кирпич и блоки',
                'parent_id' => null
            ]);
        }

        if (!$paintCategory) {
            $paintCategory = MaterialCategory::create([
                'name' => 'Краски',
                'parent_id' => null
            ]);
        }

        if (!$tilesCategory) {
            $tilesCategory = MaterialCategory::create([
                'name' => 'Плитка',
                'parent_id' => null
            ]);
        }

        $supplier = Supplier::firstOrCreate(
            ['name' => 'Manual Import'],
            [

            ]
        );

        $materials = [
            [
                'name' => 'И сан',
                'category_id' => $bricksCategory->id,
                'description' => 'Идеальный',
                'unit' => 'шт',
                'article' => 'KK-250-120-66',
                'length_mm' => 250,
                'width_mm' => 120,
                'height_mm' => 65,
                'weight_kg' => 3.6,
                'color' => 'Идеальный',
                'brand' => 'LCB',
                'supplier_id' => $supplier->id,
                'external_id' => 'manual_brick_4',
                'image_url' => 'https://www.prydwen.gg/static/400a105d43edf36022c8e4c903005870/60b4d/26_sm.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Кирпич силикатный полуторный',
                'category_id' => $bricksCategory->id,
                'description' => 'Силикатный кирпич белый',
                'unit' => 'шт',
                'article' => 'KS-250-120-88',
                'length_mm' => 250,
                'width_mm' => 120,
                'height_mm' => 88,
                'weight_kg' => 4.8,
                'color' => 'Белый',
                'brand' => 'Rauf',
                'supplier_id' => $supplier->id,
                'external_id' => 'manual_brick_2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Кирпич клинкерный',
                'category_id' => $bricksCategory->id,
                'description' => 'Клинкерный кирпич для фасада',
                'unit' => 'шт',
                'article' => 'KLC-250-120-65',
                'length_mm' => 250,
                'width_mm' => 120,
                'height_mm' => 65,
                'weight_kg' => 4.2,
                'color' => 'Терракотовый',
                'brand' => 'LegoBrick',
                'supplier_id' => $supplier->id,
                'external_id' => 'manual_brick_3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Краска акриловая белая',
                'category_id' => $paintCategory->id,
                'description' => 'Водостойкая акриловая краска для стен',
                'unit' => 'л',
                'article' => 'PAINT-ACR-WHITE',
                'weight_kg' => 1.4,
                'color' => 'Белый',
                'brand' => 'Tikkurila',
                'volume_m3' => 0.005,
                'supplier_id' => $supplier->id,
                'external_id' => 'manual_paint_1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Плитка керамическая 30x30',
                'category_id' => $tilesCategory->id,
                'description' => 'Напольная плитка серая',
                'unit' => 'шт',
                'article' => 'TILE-30x30-GRAY',
                'length_mm' => 300,
                'width_mm' => 300,
                'height_mm' => 8,
                'weight_kg' => 1.2,
                'color' => 'Серый',
                'brand' => 'Kerama Marazzi',
                'supplier_id' => $supplier->id,
                'external_id' => 'manual_tile_1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $createdCount = 0;
        $priceCount = 0;

        foreach ($materials as $materialData) {
            // Check if material already exists by external_id
            $existingMaterial = Material::where('external_id', $materialData['external_id'])->first();

            if (!$existingMaterial) {
                $material = Material::create($materialData);
                $createdCount++;

                // Add sample price
                MaterialPrice::create([
                    'material_id' => $material->id,
                    'price' => $this->getSamplePrice($materialData['name']),
                    'price_date' => now()->toDateString(),
                    'url' => null
                ]);
                $priceCount++;
            }
        }

        $this->command->info("Created {$createdCount} new materials and {$priceCount} prices");
        $this->command->info("Total materials in database: " . Material::count());
        $this->command->info("Total prices in database: " . MaterialPrice::count());
    }

    private function getSamplePrice(string $materialName): float
    {
        $prices = [
            'Кирпич керамический одинарный' => 24,
            'Кирпич силикатный полуторный' => 28,
            'Кирпич клинкерный' => 45,
            'Краска акриловая белая' => 1200,
            'Плитка керамическая 30x30' => 350,
        ];

        return $prices[$materialName] ?? 100;
    }
}
