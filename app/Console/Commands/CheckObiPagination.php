<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DiDom\Document;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class CheckObiPagination extends Command
{
    protected $signature = 'check:obi-pagination {category=fasadnye-materialy}';
    protected $description = 'Check OBI pagination structure';

    public function handle()
    {
        $category = $this->argument('category');
        $baseUrl = "https://obi.ru/strojmaterialy/{$category}";

        $client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
        ]);

        try {
            // Проверяем первую страницу
            $this->info("Checking first page: {$baseUrl}");
            $response1 = $client->get($baseUrl);
            $html1 = (string)$response1->getBody();
            $document1 = new Document($html1);

            $productCards1 = $document1->find('._2iXXi');
            $this->info("Products on first page: " . count($productCards1));

            // Простой анализ пагинации
            $this->simplePaginationAnalysis($document1);

            // Проверяем вторую страницу
            $page2Url = $baseUrl . '?page=2';
            $this->info("\n" . str_repeat("=", 50));
            $this->info("Checking second page: {$page2Url}");

            try {
                $response2 = $client->get($page2Url);
                $html2 = (string)$response2->getBody();
                $document2 = new Document($html2);

                $productCards2 = $document2->find('._2iXXi');
                $this->info("Products on second page: " . count($productCards2));

                if (count($productCards2) > 0) {
                    $this->info("✅ SUCCESS: Second page works! Found " . count($productCards2) . " products");

                    // Сравниваем товары
                    $this->comparePages($document1, $document2);
                } else {
                    $this->warn("⚠️ Second page returned but has no products");
                }

            } catch (\Exception $e) {
                $this->error("❌ ERROR accessing second page: " . $e->getMessage());
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }

        return 0;
    }

    private function simplePaginationAnalysis(Document $document)
    {
        $this->info("\nSimple pagination analysis:");

        // Ищем ссылки с page= параметром
        $pageLinks = $document->find('a[href*="page="]');
        $this->info("Links with 'page=' parameter: " . count($pageLinks));

        foreach ($pageLinks as $link) {
            $href = $link->getAttribute('href');
            $text = trim($link->text());
            $this->info(" - '{$text}' -> {$href}");
        }

        // Ищем кнопки навигации
        $navigationTexts = ['далее', 'next', '›', '»', '>', 'следующая', 'вперед'];
        foreach ($navigationTexts as $text) {
            $elements = $document->find("a, button, span");
            $found = [];
            foreach ($elements as $element) {
                if (stripos($element->text(), $text) !== false) {
                    $found[] = $element;
                }
            }
            if (count($found) > 0) {
                $this->info("Navigation text '{$text}': " . count($found) . " found");
            }
        }

        // Ищем элементы с классами связанными с пагинацией
        $paginationClasses = ['pagination', 'page', 'navigation', 'pager'];
        foreach ($paginationClasses as $class) {
            $elements = $document->find("[class*='{$class}']");
            if (count($elements) > 0) {
                $this->info("Elements with class '{$class}': " . count($elements));
            }
        }
    }

    private function comparePages(Document $doc1, Document $doc2)
    {
        $this->info("\nComparing pages:");

        $products1 = $doc1->find('._2iXXi');
        $products2 = $doc2->find('._2iXXi');

        // Получаем ID товаров с первой страницы
        $ids1 = [];
        foreach ($products1 as $product) {
            $nameElement = $product->first('._1UlGi a._1FP_W');
            if ($nameElement) {
                $url = $nameElement->getAttribute('href');
                preg_match('/(\d+)(?:\?|$)/', $url, $matches);
                if (isset($matches[1])) {
                    $ids1[] = $matches[1];
                }
            }
        }

        // Получаем ID товаров со второй страницы
        $ids2 = [];
        foreach ($products2 as $product) {
            $nameElement = $product->first('._1UlGi a._1FP_W');
            if ($nameElement) {
                $url = $nameElement->getAttribute('href');
                preg_match('/(\d+)(?:\?|$)/', $url, $matches);
                if (isset($matches[1])) {
                    $ids2[] = $matches[1];
                }
            }
        }

        $this->info("Unique products on page 1: " . count($ids1));
        $this->info("Unique products on page 2: " . count($ids2));

        // Проверяем есть ли пересечения
        $intersection = array_intersect($ids1, $ids2);
        $this->info("Overlapping products: " . count($intersection));

        if (count($intersection) === 0) {
            $this->info("✅ GOOD: No duplicate products between pages");
        } else {
            $this->warn("⚠️ WARNING: " . count($intersection) . " duplicate products found");
        }
    }
}
