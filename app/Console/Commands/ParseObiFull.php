<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ObiParserService;
use Illuminate\Support\Str;

class ParseObiFull extends Command
{
    protected $signature = 'parse:obi-full
                            {--category=fasadnye-materialy}
                            {--limit=100}
                            {--all-pages}
                            {--with-rates}';

    protected $description = 'Parse OBI with categories and consumption rates';

    public function handle()
    {
        $this->info('🚀 Starting full OBI parser...');

        $parser = new ObiParserService();
        $category = $this->option('category');
        $limit = (int)$this->option('limit');
        $allPages = $this->option('all-pages');
        $withRates = $this->option('with-rates');

        $this->info("📦 Category: {$category}");
        $this->info("📊 Limit: {$limit} products");
        $this->info("📄 All pages: " . ($allPages ? 'Yes' : 'No'));
        $this->info("📏 With rates: " . ($withRates ? 'Yes' : 'No'));

        // Парсим материалы
        $this->info("\n🔄 Parsing products...");
        $products = $parser->parseCategory($category, $limit, $allPages);

        if (empty($products)) {
            $this->error('❌ No products found or parsing failed');
            $this->info('💡 Tip: Check if the category exists and website is accessible');
            return 1;
        }

        $this->info('✅ Found ' . count($products) . ' products');

        // Сохраняем в БД
        $this->info("\n💾 Saving to database...");
        if ($withRates) {
            $results = $parser->saveToDatabaseWithCategories($products, $category);
        } else {
            $results = [
                'materials' => $parser->saveToDatabase($products),
                'category' => null,
                'consumption_rates' => []
            ];
        }

        // Показываем результаты
        $this->showResults($results, $products);

        return 0;
    }

    private function showResults(array $results, array $products)
    {
        $this->info("\n🎯 PARSING RESULTS:");
        $this->info("====================");

        $this->info("📦 Materials saved: " . count($results['materials']));

        if (isset($results['category']) && $results['category']) {
            $this->info("📁 Category: " . $results['category']->name);
        } else {
            $this->info("📁 Category: Not created");
        }

        if (isset($results['consumption_rates'])) {
            $this->info("📏 Consumption rates created: " . count($results['consumption_rates']));
        }

        // Показываем примеры материалов
        if (count($products) > 0) {
            $this->info("\n📋 Sample materials (first 10):");
            $sampleProducts = array_slice($products, 0, 10);

            $this->table(
                ['Name', 'Price', 'Unit', 'External ID'],
                array_map(function($product) {
                    return [
                        Str::limit($product['name'], 40),
                        $product['price'] ? $product['price'] . ' ₽' : 'N/A',
                        $product['unit'],
                        $product['external_id'] ?? 'N/A'
                    ];
                }, $sampleProducts)
            );
        }

        $this->info("\n🎊 Parsing completed successfully!");
    }
}
