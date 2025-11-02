<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ObiParserService;
use App\Models\MaterialCategory;
use App\Models\Material;
use DiDom\Document;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class ParseAllObiCategories extends Command
{
    protected $signature = 'parse:all-obi
                            {--limit=50 : Products per category}
                            {--pages=2 : Pages per category}
                            {--with-rates : Create consumption rates}
                            {--skip-existing : Skip categories with existing materials}
                            {--categories= : Specific categories (comma separated)}
                            {--scan : Force rescan categories from OBI}';

    protected $description = 'Parse all OBI categories automatically';

    private Client $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
        ]);
    }

    public function handle()
    {
        $this->info('🚀 Starting automatic parsing of all OBI categories...');

        $parser = new ObiParserService();
        $limit = (int)$this->option('limit');
        $pages = (int)$this->option('pages');
        $withRates = $this->option('with-rates');
        $skipExisting = $this->option('skip-existing');
        $specificCategories = $this->option('categories');
        $forceScan = $this->option('scan');

        $this->showConfig($limit, $pages, $withRates, $skipExisting);

        // Получаем категории для парсинга (сканируем с сайта OBI)
        $categoriesToParse = $this->getCategoriesToParse($specificCategories, $skipExisting, $forceScan);

        if (empty($categoriesToParse)) {
            $this->error('❌ No categories found to parse');
            return 1;
        }

        $this->info("\n📋 Categories to parse: " . count($categoriesToParse));

        $totalResults = [
            'categories' => 0,
            'materials' => 0,
            'rates' => 0,
            'failed' => 0
        ];

        // Парсим каждую категорию
        foreach ($categoriesToParse as $category) {
            $this->parseCategory($parser, $category, $limit, $pages, $withRates, $totalResults);
        }

        // Показываем итоги
        $this->showFinalResults($totalResults);

        return 0;
    }

    private function showConfig(int $limit, int $pages, bool $withRates, bool $skipExisting): void
    {
        $this->info("⚙️  Configuration:");
        $this->info("   📊 Products per category: {$limit}");
        $this->info("   📄 Pages per category: {$pages}");
        $this->info("   📏 With consumption rates: " . ($withRates ? 'Yes' : 'No'));
        $this->info("   ⏭️ Skip existing: " . ($skipExisting ? 'Yes' : 'No'));
    }

    private function getCategoriesToParse(?string $specificCategories, bool $skipExisting, bool $forceScan): array
    {
        if ($specificCategories) {
            return $this->getSpecificCategories($specificCategories);
        }

        return $this->getCategoriesFromObi($skipExisting, $forceScan);
    }

    private function getSpecificCategories(string $categoriesList): array
    {
        $categorySlugs = array_map('trim', explode(',', $categoriesList));
        $categories = [];

        foreach ($categorySlugs as $slug) {
            $categories[] = [
                'slug' => $slug,
                'name' => $this->slugToName($slug)
            ];
        }

        $this->info("🎯 Specific categories: " . implode(', ', $categorySlugs));
        return $categories;
    }

    private function getCategoriesFromObi(bool $skipExisting, bool $forceScan): array
    {
        $this->info("\n🔍 Scanning OBI categories...");

        try {
            $url = 'https://obi.ru/strojmaterialy';
            $response = $this->client->get($url);
            $html = (string)$response->getBody();
            $document = new Document($html);

            $categories = $document->find('a[href*="/strojmaterialy/"]');

            $categoryData = [];
            foreach ($categories as $category) {
                $href = $category->getAttribute('href');
                $name = trim($category->text());

                // Фильтруем категории
                if (strpos($href, '/strojmaterialy/') !== false &&
                    !strpos($href, '?') &&
                    strlen($name) > 2 &&
                    !in_array($name, ['Стройматериалы', 'Все товары', 'Акции', 'Новинки'])) {

                    $slug = str_replace('/strojmaterialy/', '', $href);
                    $slug = rtrim($slug, '/');

                    // Извлекаем количество товаров из названия (если есть)
                    $cleanName = preg_replace('/\s*\(\d+\)\s*$/', '', $name);
                    preg_match('/\((\d+)\)/', $name, $matches);
                    $productCount = $matches[1] ?? null;

                    $categoryData[] = [
                        'name' => $cleanName,
                        'slug' => $slug,
                        'product_count' => $productCount,
                        'url' => 'https://obi.ru' . $href
                    ];
                }
            }

            // Убираем дубликаты
            $uniqueCategories = [];
            foreach ($categoryData as $category) {
                $uniqueCategories[$category['slug']] = $category;
            }

            $categories = array_values($uniqueCategories);

            $this->info("✅ Found " . count($categories) . " categories on OBI");

            // Сортируем по количеству товаров (если есть)
            usort($categories, function($a, $b) {
                $countA = $a['product_count'] ?? 0;
                $countB = $b['product_count'] ?? 0;
                return $countB - $countA;
            });

            // Показываем найденные категории
            $this->showScannedCategories($categories);

            // Создаем категории в БД
            $this->createCategoriesInDb($categories);

            // Фильтруем существующие если нужно
            if ($skipExisting) {
                $categories = $this->filterExistingCategories($categories);
            }

            return $categories;

        } catch (\Exception $e) {
            $this->error("❌ Error scanning categories: " . $e->getMessage());
            return [];
        }
    }

    private function showScannedCategories(array $categories): void
    {
        $this->info("\n📋 Scanned categories from OBI:");

        $tableData = [];
        foreach ($categories as $category) {
            $productCount = $category['product_count'] ? "({$category['product_count']} товаров)" : "";
            $tableData[] = [
                $category['name'],
                $category['slug'],
                $productCount
            ];
        }

        $this->table(
            ['Name', 'Slug', 'Products'],
            $tableData
        );
    }

    private function createCategoriesInDb(array $categories): void
    {
        $this->info("\n💾 Creating categories in database...");

        // Создаем родительскую категорию
        $parentCategory = MaterialCategory::firstOrCreate(
            ['name' => 'Стройматериалы OBI'],
            ['parent_id' => null]
        );

        $createdCount = 0;
        $existingCount = 0;

        foreach ($categories as $category) {
            $existingCategory = MaterialCategory::where('name', $category['name'])->first();

            if (!$existingCategory) {
                MaterialCategory::create([
                    'name' => $category['name'],
                    'parent_id' => $parentCategory->id,
                ]);
                $createdCount++;
            } else {
                $existingCount++;
            }
        }

        $this->info("✅ Categories created: {$createdCount}, existing: {$existingCount}");
    }

    private function filterExistingCategories(array $categories): array
    {
        $filtered = [];

        foreach ($categories as $category) {
            $materialCount = Material::whereHas('category', function($query) use ($category) {
                $query->where('name', $category['name']);
            })->count();

            if ($materialCount === 0) {
                $filtered[] = $category;
            } else {
                $this->info("⏭️ Skipping {$category['name']} - already has {$materialCount} materials");
            }
        }

        $this->info("📊 After filtering: " . count($filtered) . " categories to parse");
        return $filtered;
    }

    private function slugToName(string $slug): string
    {
        // Базовый маппинг для специфических случаев
        $mapping = [
            'fasadnye-materialy' => 'Фасадные материалы',
            'kraski' => 'Краски',
            'plitka' => 'Плитка',
        ];

        return $mapping[$slug] ?? Str::title(str_replace('-', ' ', $slug));
    }

    private function parseCategory(ObiParserService $parser, array $category, int $limit, int $pages, bool $withRates, array &$totalResults): void
    {
        $this->info("\n" . str_repeat('=', 60));
        $this->info("🔄 Parsing: {$category['name']} ({$category['slug']})");
        if ($category['product_count']) {
            $this->info("   📊 Expected: ~{$category['product_count']} products");
        }
        $this->info(str_repeat('=', 60));

        try {
            // Парсим категорию
            $allPages = $pages > 1;
            $products = $parser->parseCategory($category['slug'], $limit, $allPages);

            if (empty($products)) {
                $this->error("❌ No products found in {$category['name']}");
                $totalResults['failed']++;
                return;
            }

            $this->info("✅ Found " . count($products) . " products");

            // Сохраняем в БД
            if ($withRates) {
                $results = $parser->saveToDatabaseWithCategories($products, $category['slug']);
            } else {
                $results = [
                    'materials' => $parser->saveToDatabase($products),
                    'category' => null,
                    'consumption_rates' => []
                ];
            }

            // Обновляем статистику
            $totalResults['categories']++;
            $totalResults['materials'] += count($results['materials']);
            $totalResults['rates'] += count($results['consumption_rates']);

            // Показываем результаты категории
            $this->showCategoryResults($results, $products);

            // Пауза между категориями
            sleep(2);

        } catch (\Exception $e) {
            $this->error("❌ Error parsing {$category['name']}: " . $e->getMessage());
            $totalResults['failed']++;
        }
    }

    private function showCategoryResults(array $results, array $products): void
    {
        $this->info("📊 Category results:");
        $this->info("   📦 Materials saved: " . count($results['materials']));

        if ($results['category']) {
            $this->info("   📁 Category: " . $results['category']->name);
        }

        if (!empty($results['consumption_rates'])) {
            $this->info("   📏 Consumption rates: " . count($results['consumption_rates']));
        }

        // Показываем несколько примеров
        if (count($products) > 0) {
            $sampleCount = min(3, count($products));
            $this->info("   📋 Sample products:");

            for ($i = 0; $i < $sampleCount; $i++) {
                $product = $products[$i];
                $this->info("      • " . Str::limit($product['name'], 50) . " - " . ($product['price'] ?? 'N/A') . ' ₽');
            }
        }
    }

    private function showFinalResults(array $totalResults): void
    {
        $this->info("\n" . str_repeat('⭐', 60));
        $this->info("🎊 AUTOMATIC PARSING COMPLETED!");
        $this->info(str_repeat('⭐', 60));

        $this->info("📈 Final Results:");
        $this->info("   ✅ Categories processed: {$totalResults['categories']}");
        $this->info("   📦 Total materials saved: {$totalResults['materials']}");
        $this->info("   📏 Consumption rates created: {$totalResults['rates']}");
        $this->info("   ❌ Failed categories: {$totalResults['failed']}");

        $this->info("\n💾 Database now contains:");
        $this->info("   📁 Categories: " . MaterialCategory::count());
        $this->info("   📦 Materials: " . Material::count());
        $this->info("   💰 Prices: " . \App\Models\MaterialPrice::count());
        $this->info("   📏 Consumption rates: " . \App\Models\MaterialConsumptionRate::count());

        $this->info("\n🎯 Next steps:");
        $this->info("   • Run: php artisan parse:all-obi --with-rates --limit=100");
        $this->info("   • Run: php artisan parse:all-obi --skip-existing");
        $this->info("   • Force rescan: php artisan parse:all-obi --scan");
    }
}
