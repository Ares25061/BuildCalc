<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ObiParserService;
use Illuminate\Support\Str;

class ParseObiMaterials extends Command
{
    // В ParseObiMaterials.php обновите signature
    protected $signature = 'parse:obi
                        {--limit=200}
                        {--category=facade}
                        {--all-pages : Parse all available pages}
                        {--max-pages=20 : Maximum pages to parse}';

    protected $description = 'Parse materials from OBI website';

    // В ParseObiMaterials.php
    public function handle()
    {
        $this->info('🚀 Starting OBI parser...');

        $parser = new ObiParserService();
        $limit = (int)$this->option('limit');
        $category = $this->option('category');
        $allPages = $this->option('all-pages');
        $maxPages = (int)$this->option('max-pages');

        if ($allPages) {
            $this->info("📄 Parsing up to {$maxPages} pages from category: {$category} (up to {$limit} products)");
        } else {
            $this->info("📄 Parsing first page from category: {$category} (up to {$limit} products)");
        }

        // Передаем maxPages в парсер
        $products = $parser->parseFacadeMaterials($limit, $allPages, $maxPages);

        if (empty($products)) {
            $this->error('❌ No products found or parsing failed');
            $this->info('💡 Tip: Try running php artisan check:obi-pagination first');
            return 1;
        }

        $this->info('✅ Found ' . count($products) . ' unique products');

        // Показываем таблицу с результатами (первые 20 для удобства)
        $displayProducts = array_slice($products, 0, 20);
        $this->table(
            ['Name', 'Price', 'External ID', 'Unit'],
            array_map(function($product) {
                return [
                    Str::limit($product['name'], 50),
                    $product['price'] ? $product['price'] . ' ₽' : 'N/A',
                    $product['external_id'] ?? 'N/A',
                    $product['unit']
                ];
            }, $displayProducts)
        );

        if (count($products) > 20) {
            $this->info("📋 ... and " . (count($products) - 20) . " more products");
        }

        // Спрашиваем подтверждение для сохранения в БД
        if ($this->confirm('💾 Save these products to database?', true)) {
            $saved = $parser->saveToDatabase($products);
            $this->info('💾 Successfully saved ' . count($saved) . ' products to database');
        }

        $this->info('🎊 OBI parsing completed!');
        return 0;
    }
}
