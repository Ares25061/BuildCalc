<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ObiParserService;
use App\Models\Material;
use Illuminate\Support\Str;

class DebugObiParser extends Command
{
    protected $signature = 'debug:parser';
    protected $description = 'Debug OBI parser issues';

    public function handle()
    {
        $this->info("Debugging OBI parser...");

        // Проверяем существующие материалы
        $existingMaterials = Material::where('supplier_id', 1)->get();
        $this->info("Existing materials in DB: " . $existingMaterials->count());

        if ($existingMaterials->count() > 0) {
            $this->table(
                ['ID', 'Name', 'External ID'],
                $existingMaterials->map(function($material) {
                    return [
                        $material->id,
                        Str::limit($material->name, 40),
                        $material->external_id
                    ];
                })->toArray()
            );
        }

        // Тестируем парсинг
        $parser = new ObiParserService();
        $products = $parser->parseFacadeMaterials(5);

        $this->info("Parsed products: " . count($products));

        // Проверяем external_id у распарсенных продуктов
        $this->info("\nChecking parsed products external IDs:");
        foreach ($products as $index => $product) {
            $this->info("Product {$index}: " . ($product['external_id'] ?? 'NULL'));
        }

        // Пробуем сохранить вручную
        $this->info("\nTrying manual save...");
        $savedCount = 0;

        foreach ($products as $product) {
            try {
                $existing = Material::where([
                    'supplier_id' => 1,
                    'external_id' => $product['external_id']
                ])->first();

                if ($existing) {
                    $this->info("Already exists: " . $product['name']);
                    continue;
                }

                $material = Material::create([
                    'name' => $product['name'],
                    'category_id' => null,
                    'description' => $product['description'],
                    'unit' => $product['unit'],
                    'article' => $product['article'] ?: $product['external_id'],
                    'image_url' => $product['image_url'],
                    'supplier_id' => 1,
                    'external_id' => $product['external_id'],
                ]);

                $savedCount++;
                $this->info("Saved: " . $product['name']);

            } catch (\Exception $e) {
                $this->error("Error saving: " . $e->getMessage());
            }
        }

        $this->info("Manually saved: {$savedCount} products");

        return 0;
    }
}
