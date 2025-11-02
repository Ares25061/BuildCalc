<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DiDom\Document;
use GuzzleHttp\Client;

class ListObiCategories extends Command
{
    protected $signature = 'obi:categories';
    protected $description = 'List available OBI categories';

    public function handle()
    {
        $url = 'https://obi.ru/strojmaterialy';

        $client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
        ]);

        try {
            $response = $client->get($url);
            $html = (string)$response->getBody();
            $document = new Document($html);

            $this->info("Available categories in Стройматериалы:");

            // Ищем категории
            $categories = $document->find('a[href*="/strojmaterialy/"]');

            $categoryData = [];
            foreach ($categories as $category) {
                $href = $category->getAttribute('href');
                $name = trim($category->text());

                // Фильтруем только реальные категории (исключаем главную страницу и т.д.)
                if (strpos($href, '/strojmaterialy/') !== false &&
                    !strpos($href, '?') &&
                    strlen($name) > 2 &&
                    !in_array($name, ['Стройматериалы', 'Все товары'])) {

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

            $this->info("\nUsage: php artisan parse:obi --category=slug_name");

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }

        return 0;
    }
}
