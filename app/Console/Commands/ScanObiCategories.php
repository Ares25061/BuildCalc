<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DiDom\Document;
use GuzzleHttp\Client;

class ScanObiCategories extends Command
{
    protected $signature = 'scan:obi-categories';
    protected $description = 'Scan OBI website for all available categories';

    public function handle()
    {
        $baseUrl = 'https://obi.ru/strojmaterialy';

        $client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
        ]);

        try {
            $this->info("🔍 Scanning OBI categories...");
            $response = $client->get($baseUrl);
            $html = (string)$response->getBody();
            $document = new Document($html);

            $categories = $document->find('a[href*="/strojmaterialy/"]');

            $categoryData = [];
            foreach ($categories as $category) {
                $href = $category->getAttribute('href');
                $name = trim($category->text());

                if (strpos($href, '/strojmaterialy/') !== false &&
                    !strpos($href, '?') &&
                    strlen($name) > 2 &&
                    !in_array($name, ['Стройматериалы', 'Все товары', 'Акции', 'Новинки'])) {

                    $slug = str_replace('/strojmaterialy/', '', $href);
                    $slug = rtrim($slug, '/');

                    $categoryData[] = [
                        'name' => $name,
                        'slug' => $slug,
                        'url' => 'https://obi.ru' . $href
                    ];
                }
            }

            // Убираем дубликаты
            $uniqueCategories = [];
            foreach ($categoryData as $category) {
                $uniqueCategories[$category['slug']] = $category;
            }

            $this->info("\n🎯 Found " . count($uniqueCategories) . " categories:");

            $this->table(
                ['Name', 'Slug', 'URL'],
                array_map(function($category) {
                    return [
                        $category['name'],
                        $category['slug'],
                        $category['url']
                    ];
                }, array_values($uniqueCategories))
            );

            // Показываем команду для парсинга
            $slugs = array_keys($uniqueCategories);
            $this->info("\n💡 To parse all categories, run:");
            $this->info("php artisan parse:all-obi --categories=" . implode(',', array_slice($slugs, 0, 5)));

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }

        return 0;
    }
}
