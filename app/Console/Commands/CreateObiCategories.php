<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DiDom\Document;
use GuzzleHttp\Client;
use App\Models\MaterialCategory;

class CreateObiCategories extends Command
{
    protected $signature = 'obi:create-categories';
    protected $description = 'Create material categories from OBI website';

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

            $this->info("Creating categories from OBI...");

            // Создаем родительскую категорию
            $parentCategory = MaterialCategory::firstOrCreate(
                ['name' => 'Стройматериалы OBI'],
                ['parent_id' => null]
            );

            // Ищем подкатегории
            $categories = $document->find('a[href*="/strojmaterialy/"]');

            $createdCount = 0;
            $skippedCount = 0;

            foreach ($categories as $category) {
                $href = $category->getAttribute('href');
                $name = trim($category->text());

                // Фильтруем категории
                if (strpos($href, '/strojmaterialy/') !== false &&
                    !strpos($href, '?') &&
                    strlen($name) > 2 &&
                    !in_array($name, ['Стройматериалы', 'Все товары', 'Акции', 'Новинки'])) {

                    $existingCategory = MaterialCategory::where('name', $name)->first();

                    if (!$existingCategory) {
                        MaterialCategory::create([
                            'name' => $name,
                            'parent_id' => $parentCategory->id,
                        ]);
                        $this->info("✅ Created category: {$name}");
                        $createdCount++;
                    } else {
                        $this->line("⏭️ Skipped (exists): {$name}");
                        $skippedCount++;
                    }
                }
            }

            $this->info("\n🎉 Categories creation completed!");
            $this->info("✅ Created: {$createdCount}");
            $this->info("⏭️ Skipped: {$skippedCount}");
            $this->info("📊 Total in DB: " . MaterialCategory::count());

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }

        return 0;
    }
}
