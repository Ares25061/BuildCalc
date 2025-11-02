<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DiDom\Document;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class CheckAllObiPages extends Command
{
    protected $signature = 'check:obi-all-pages
                            {category=fasadnye-materialy}
                            {--max-pages=10}';

    protected $description = 'Check all OBI pages for a category';

    public function handle()
    {
        $category = $this->argument('category');
        $maxPages = (int)$this->option('max-pages');
        $baseUrl = "https://obi.ru/strojmaterialy/{$category}";

        $client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
        ]);

        $allProducts = [];
        $page = 1;
        $uniqueIds = [];

        $this->info("🔍 Checking up to {$maxPages} pages for category: {$category}");

        while ($page <= $maxPages) {
            $url = $page === 1 ? $baseUrl : $baseUrl . '?page=' . $page;

            try {
                $this->info("\n📄 Page {$page}: {$url}");
                $response = $client->get($url);
                $html = (string)$response->getBody();
                $document = new Document($html);

                $productCards = $document->find('._2iXXi');
                $this->info("   Found " . count($productCards) . " product cards");

                // Анализируем уникальность
                $newProducts = 0;
                $duplicates = 0;

                foreach ($productCards as $card) {
                    $nameElement = $card->first('._1UlGi a._1FP_W');
                    if ($nameElement) {
                        $url = $nameElement->getAttribute('href');
                        preg_match('/(\d+)(?:\?|$)/', $url, $matches);
                        if (isset($matches[1])) {
                            $externalId = $matches[1];

                            if (!in_array($externalId, $uniqueIds)) {
                                $uniqueIds[] = $externalId;
                                $newProducts++;
                            } else {
                                $duplicates++;
                            }
                        }
                    }
                }

                $this->info("   ✅ New products: {$newProducts}");
                if ($duplicates > 0) {
                    $this->info("   ⚠️ Duplicates: {$duplicates}");
                }
                $this->info("   📊 Total unique: " . count($uniqueIds));

                // Если нет новых товаров, останавливаемся
                if ($newProducts === 0 && $page > 1) {
                    $this->info("🛑 No new products on page {$page}, stopping");
                    break;
                }

                $page++;
                sleep(1); // Пауза

            } catch (\Exception $e) {
                $this->error("❌ Error on page {$page}: " . $e->getMessage());
                break;
            }
        }

        $this->info("\n🎯 FINAL RESULTS:");
        $this->info("   Pages processed: " . ($page - 1));
        $this->info("   Total unique products: " . count($uniqueIds));

        return 0;
    }
}
