<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Material;
use App\Models\MaterialPrice;
use App\Models\MaterialCategory;
use App\Models\WorkType;
use App\Models\MaterialConsumptionRate;
use App\Models\Supplier;
use DiDom\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Str;

class ObiParserService
{
    private string $baseUrl = 'https://obi.ru';
    private int $supplierId;
    private Client $client;
    private array $categoryMapping;

    public function __construct()
    {
        $supplier = Supplier::where('name', 'OBI')->first();
        $this->supplierId = $supplier ? $supplier->id : 1;

        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
            'pool' => [
                'max_connections' => 10, // Максимум параллельных соединений
            ],
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'ru-RU,ru;q=0.9,en;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
            ],
        ]);

        $this->categoryMapping = $this->loadCategoryMapping();
    }

    private function loadCategoryMapping(): array
    {
        return [
            'fasadnye-materialy' => [
                'category_name' => 'Фасадные материалы',
                'work_type' => 'Монтаж сайдинга',
                'consumption_rate' => 1.05
            ],
            'krovlja' => [
                'category_name' => 'Кровля',
                'work_type' => 'Устройство кровли',
                'consumption_rate' => 1.03
            ],
            'vodostok' => [
                'category_name' => 'Водосток',
                'work_type' => 'Монтажные работы',
                'consumption_rate' => 1.02
            ],
            'naruzhnaja-kanalizacija' => [
                'category_name' => 'Наружная канализация',
                'work_type' => 'Монтажные работы',
                'consumption_rate' => 1.01
            ],
            'teploizoljacija' => [
                'category_name' => 'Теплоизоляция',
                'work_type' => 'Утепление фасада',
                'consumption_rate' => 1.1
            ],
            'shumoizoljacija' => [
                'category_name' => 'Шумоизоляция',
                'work_type' => 'Монтажные работы',
                'consumption_rate' => 1.05
            ],
            'gidroizoljacija' => [
                'category_name' => 'Гидроизоляция',
                'work_type' => 'Гидроизоляционные работы',
                'consumption_rate' => 1.08
            ],
            'paroizoljacija' => [
                'category_name' => 'Пароизоляция',
                'work_type' => 'Монтажные работы',
                'consumption_rate' => 1.04
            ],
            'metalloprokat' => [
                'category_name' => 'Металлопрокат',
                'work_type' => 'Металлоконструкции',
                'consumption_rate' => 1.02
            ],
            'suhie-stroitelnye-smesi' => [
                'category_name' => 'Сухие строительные смеси',
                'work_type' => 'Штукатурные работы',
                'consumption_rate' => 1.7
            ],
            'bloki-stroitelnye' => [
                'category_name' => 'Блоки строительные',
                'work_type' => 'Кладка блоков',
                'consumption_rate' => 1.0
            ],
            'listovye-materialy' => [
                'category_name' => 'Листовые материалы',
                'work_type' => 'Монтажные работы',
                'consumption_rate' => 1.06
            ],
            'stroitelnoe-oborudovanie' => [
                'category_name' => 'Строительное оборудование',
                'work_type' => 'Монтажные работы',
                'consumption_rate' => 0.01
            ],
            'stroitelnye-rashodnye-materialy' => [
                'category_name' => 'Строительные расходные материалы',
                'work_type' => 'Монтажные работы',
                'consumption_rate' => 0.5
            ],
            'podvesnye-potolki' => [
                'category_name' => 'Подвесные потолки',
                'work_type' => 'Монтаж потолков',
                'consumption_rate' => 1.03
            ],
            // Резервные маппинги
            'kraski' => [
                'category_name' => 'Краски',
                'work_type' => 'Покраска',
                'consumption_rate' => 0.15
            ],
            'plitka' => [
                'category_name' => 'Плитка',
                'work_type' => 'Укладка плитки',
                'consumption_rate' => 1.1
            ],
        ];
    }

    public function parseCategory(string $categorySlug, int $limit = 100, bool $allPages = false): array
    {
        $baseUrl = $this->baseUrl . '/strojmaterialy/' . $categorySlug;
        $allProducts = [];
        $page = 1;
        $maxPages = $allPages ? 10 : 1;
        $uniqueExternalIds = [];

        while (count($allProducts) < $limit && $page <= $maxPages) {
            $url = $page === 1 ? $baseUrl : $baseUrl . '?page=' . $page;

            try {
                Log::info("Parsing page {$page}: {$url}");
                $html = $this->fetchPage($url);

                $products = $this->parseProducts($html, $limit - count($allProducts), $uniqueExternalIds);

                if (empty($products)) {
                    Log::info("No products found on page {$page}, stopping");
                    break;
                }

                foreach ($products as $product) {
                    if (count($allProducts) >= $limit) break;
                    $allProducts[] = $product;
                }

                Log::info("Page {$page}: added " . count($products) . " products, total: " . count($allProducts));

                if ($allPages) {
                    $page++;
                    sleep(1); // Только между страницами
                } else {
                    break;
                }

            } catch (\Exception $e) {
                Log::error("Error parsing page {$page}: " . $e->getMessage());
                break;
            }
        }

        return $allProducts;
    }

    private function fetchPage(string $url): string
    {
        $response = $this->client->get($url);
        return (string)$response->getBody();
    }

    private function parseProducts(string $html, int $limit, array &$uniqueExternalIds = []): array
    {
        $products = [];
        $document = new Document($html);

        $productCards = $document->find('._2iXXi');
        Log::info("Found product cards: " . count($productCards));

        $productUrls = [];
        $basicProducts = [];

        // Сначала собираем базовую информацию и URLs
        foreach ($productCards as $card) {
            if (count($basicProducts) >= $limit) break;

            $product = $this->parseProductCardBasic($card);
            if ($product && !empty($product['name']) && !empty($product['price'])) {

                $externalId = $product['external_id'];
                if (!$externalId) {
                    $externalId = 'temp_' . md5($product['name'] . $product['price']);
                    $product['external_id'] = $externalId;
                }

                if (in_array($externalId, $uniqueExternalIds)) {
                    continue;
                }

                $uniqueExternalIds[] = $externalId;
                $basicProducts[] = $product;

                // Сохраняем URL для параллельного парсинга
                if ($product['product_url']) {
                    $productUrls[] = $product['product_url'];
                }
            }
        }

        // Параллельный парсинг деталей товаров
        if (!empty($productUrls)) {
            $detailedProducts = $this->parseProductsDetailsParallel($productUrls, $basicProducts);
            $products = $detailedProducts;
        } else {
            $products = $basicProducts;
        }

        return array_slice($products, 0, $limit);
    }

    private function parseProductCardBasic($card): ?array
    {
        try {
            // Название товара
            $nameElement = $card->first('._1UlGi a._1FP_W');
            if (!$nameElement) {
                return null;
            }
            $name = trim($nameElement->text());

            // Цена
            $priceElement = $card->first('._3IeOW');
            if (!$priceElement) {
                return null;
            }
            $price = $this->parsePrice($priceElement->text());

            // Ссылка на товар
            $productUrl = $nameElement->getAttribute('href');
            if ($productUrl && !Str::startsWith($productUrl, 'http')) {
                $productUrl = $this->baseUrl . $productUrl;
            }

            // ID товара из URL
            $externalId = $this->extractIdFromUrl($productUrl);

            // Изображение
            $imageUrl = $this->extractImageUrl($card);

            // Единица измерения
            $unitElement = $card->first('._3SDdj');
            $unit = $unitElement ? trim($unitElement->text()) : 'шт';

            return [
                'name' => $name,
                'price' => $price,
                'image_url' => $imageUrl,
                'product_url' => $productUrl,
                'external_id' => $externalId,
                'unit' => $unit,
                'description' => '',
                'article' => $externalId ?: Str::slug($name),
                'brand' => null,
                'color' => null,
                'length_mm' => null,
                'width_mm' => null,
                'height_mm' => null,
                'weight_kg' => null,
                'volume_m3' => null,
            ];

        } catch (\Exception $e) {
            Log::error('Error parsing product card: ' . $e->getMessage());
            return null;
        }
    }

    private function parseProductsDetailsParallel(array $urls, array $basicProducts): array
    {
        $products = [];
        $urlToProductMap = [];

        // Создаем маппинг URL -> продукт
        foreach ($basicProducts as $product) {
            if ($product['product_url']) {
                $urlToProductMap[$product['product_url']] = $product;
            }
        }

        // Создаем промисы для параллельных запросов
        $promises = [];
        foreach ($urls as $url) {
            $promises[$url] = $this->client->getAsync($url, [
                'timeout' => 15,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ]
            ]);
        }

        // Обрабатываем результаты - ИСПРАВЛЕННАЯ СТРОКА
        $results = Utils::settle($promises)->wait();

        foreach ($results as $url => $result) {
            if ($result['state'] === 'fulfilled') {
                try {
                    $response = $result['value'];
                    $html = (string)$response->getBody();
                    $details = $this->parseProductDetailsFromHtml($html);

                    // Объединяем базовые данные с деталями
                    if (isset($urlToProductMap[$url])) {
                        $product = array_merge($urlToProductMap[$url], $details);

                        // Вычисляем объем если есть размеры
                        if (!$product['volume_m3'] && ($product['length_mm'] || $product['width_mm'] || $product['height_mm'])) {
                            $product['volume_m3'] = $this->calculateVolume($product);
                        }

                        $products[] = $product;
                    }

                } catch (\Exception $e) {
                    Log::error("Error processing product details for {$url}: " . $e->getMessage());
                    // Используем базовые данные если детальный парсинг не удался
                    if (isset($urlToProductMap[$url])) {
                        $products[] = $urlToProductMap[$url];
                    }
                }
            } else {
                Log::error("Failed to fetch product details for {$url}: " . $result['reason']);
                // Используем базовые данные если запрос не удался
                if (isset($urlToProductMap[$url])) {
                    $products[] = $urlToProductMap[$url];
                }
            }
        }

        // Добавляем продукты без URL (если такие есть)
        foreach ($basicProducts as $product) {
            if (!$product['product_url'] && !in_array($product, $products, true)) {
                $products[] = $product;
            }
        }

        return $products;
    }

    private function parseProductDetailsFromHtml(string $html): array
    {
        $document = new Document($html);

        $details = [
            'brand' => null,
            'color' => null,
            'length_mm' => null,
            'width_mm' => null,
            'height_mm' => null,
            'weight_kg' => null,
            'volume_m3' => null,
            'description' => ''
        ];

        // Парсим характеристики из секции "Характеристики"
        $characteristics = $document->find('#Characteristics dl');

        foreach ($characteristics as $charSection) {
            $items = $charSection->find('div._22uJs');

            foreach ($items as $item) {
                $keyElement = $item->first('dt.ImGAo');
                $valueElement = $item->first('dd._gJlB span');

                if (!$keyElement || !$valueElement) {
                    continue;
                }

                $key = trim($keyElement->text());
                $value = trim($valueElement->text());

                $this->mapCharacteristic($details, $key, $value);
            }
        }

        // Парсим описание
        $descriptionElement = $document->first('div[data-testid="product-description"]');
        if ($descriptionElement) {
            $details['description'] = trim($descriptionElement->text());
        }

        return $details;
    }

    private function parseProductCard($card): ?array
    {
        try {
            // Название товара
            $nameElement = $card->first('._1UlGi a._1FP_W');
            if (!$nameElement) {
                return null;
            }
            $name = trim($nameElement->text());

            // Цена
            $priceElement = $card->first('._3IeOW');
            if (!$priceElement) {
                return null;
            }
            $price = $this->parsePrice($priceElement->text());

            // Ссылка на товар
            $productUrl = $nameElement->getAttribute('href');
            if ($productUrl && !Str::startsWith($productUrl, 'http')) {
                $productUrl = $this->baseUrl . $productUrl;
            }

            // ID товара из URL
            $externalId = $this->extractIdFromUrl($productUrl);

            // Изображение
            $imageUrl = $this->extractImageUrl($card);

            // Единица измерения
            $unitElement = $card->first('._3SDdj');
            $unit = $unitElement ? trim($unitElement->text()) : 'шт';

            // Базовые данные
            $productData = [
                'name' => $name,
                'price' => $price,
                'image_url' => $imageUrl,
                'product_url' => $productUrl,
                'external_id' => $externalId,
                'unit' => $unit,
                'description' => '',
                'article' => $externalId ?: Str::slug($name),
                'brand' => null,
                'color' => null,
                'length_mm' => null,
                'width_mm' => null,
                'height_mm' => null,
                'weight_kg' => null,
                'volume_m3' => null,
            ];

            // Парсим детальную информацию если есть ссылка
            if ($productUrl) {
                $details = $this->parseProductDetails($productUrl);
                $productData = array_merge($productData, $details);

                // Вычисляем объем если есть размеры
                if (!$productData['volume_m3'] && ($productData['length_mm'] || $productData['width_mm'] || $productData['height_mm'])) {
                    $productData['volume_m3'] = $this->calculateVolume($productData);
                }
            }

            return $productData;

        } catch (\Exception $e) {
            Log::error('Error parsing product card: ' . $e->getMessage());
            return null;
        }
    }

    private function extractImageUrl($card): ?string
    {
        $sourceElement = $card->first('source');
        if ($sourceElement) {
            $srcset = $sourceElement->getAttribute('srcset');
            if ($srcset) {
                $urls = explode(' ', $srcset);
                return $urls[0] ?? null;
            }
        }

        $imgElement = $card->first('img');
        if ($imgElement) {
            return $imgElement->getAttribute('src');
        }

        return null;
    }

    private function parsePrice(string $priceString): ?float
    {
        $cleanPrice = html_entity_decode($priceString, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cleanPrice = str_replace(['&nbsp;', '₽', ' ', ' '], '', $cleanPrice);
        $cleanPrice = preg_replace('/[^\d,.]/', '', $cleanPrice);
        $cleanPrice = str_replace(',', '.', $cleanPrice);

        return $cleanPrice ? floatval($cleanPrice) : null;
    }

    private function extractIdFromUrl(?string $url): ?string
    {
        if (!$url) return null;

        preg_match('/(\d+)(?:\?|$)/', $url, $matches);
        return $matches[1] ?? null;
    }

    public function saveToDatabase(array $products): array
    {
        $saved = [];

        foreach ($products as $productData) {
            try {
                $externalId = $productData['external_id'];

                if (!$externalId) {
                    continue;
                }

                $existingMaterial = Material::where([
                    'supplier_id' => $this->supplierId,
                    'external_id' => $externalId
                ])->first();

                if ($existingMaterial) {
                    $this->updatePrice($existingMaterial->id, $productData['price']);
                    $saved[] = $existingMaterial;
                    continue;
                }

                $material = Material::create([
                    'name' => $productData['name'],
                    'category_id' => null,
                    'description' => $productData['description'],
                    'unit' => $productData['unit'],
                    'article' => $productData['article'] ?: $externalId,
                    'image_url' => $productData['image_url'],
                    'supplier_id' => $this->supplierId,
                    'external_id' => $externalId,
                ]);

                $this->updatePrice($material->id, $productData['price']);
                $saved[] = $material;

            } catch (\Exception $e) {
                Log::error('Error saving product: ' . $e->getMessage());
            }
        }

        return $saved;
    }

    public function saveToDatabaseWithCategories(array $products, string $categorySlug): array
    {
        $results = [
            'materials' => [],
            'prices' => [],
            'category' => null,
            'consumption_rates' => []
        ];

        // Создаем категорию
        $category = $this->findOrCreateCategory($categorySlug);
        $results['category'] = $category;

        // Сохраняем материалы и цены
        foreach ($products as $productData) {
            try {
                $externalId = $productData['external_id'];

                if (!$externalId) {
                    continue;
                }

                $existingMaterial = Material::where([
                    'supplier_id' => $this->supplierId,
                    'external_id' => $externalId
                ])->first();

                if ($existingMaterial) {
                    // Обновляем существующий материал с новыми данными
                    $this->updateMaterialWithDetails($existingMaterial, $productData);
                    $this->updatePrice($existingMaterial->id, $productData['price']);
                    $results['materials'][] = $existingMaterial;
                    continue;
                }

                $material = Material::create([
                    'name' => $productData['name'],
                    'category_id' => $category ? $category->id : null,
                    'description' => $productData['description'],
                    'unit' => $productData['unit'],
                    'article' => $productData['article'] ?: $externalId,
                    'image_url' => $productData['image_url'],
                    'supplier_id' => $this->supplierId,
                    'external_id' => $externalId,
                    'length_mm' => $productData['length_mm'],
                    'width_mm' => $productData['width_mm'],
                    'height_mm' => $productData['height_mm'],
                    'weight_kg' => $productData['weight_kg'],
                    'color' => $productData['color'],
                    'brand' => $productData['brand'],
                    'volume_m3' => $productData['volume_m3'],
                ]);

                $this->updatePrice($material->id, $productData['price']);
                $results['materials'][] = $material;

            } catch (\Exception $e) {
                Log::error('Error saving product: ' . $e->getMessage());
            }
        }

        // Создаем нормы расхода
        if ($category) {
            $rates = $this->createConsumptionRates($results['materials'], $categorySlug);
            $results['consumption_rates'] = $rates;
        }

        return $results;
    }

    private function updateMaterialWithDetails(Material $material, array $productData): void
    {
        $updateData = [];

        $fields = [
            'description', 'unit', 'article', 'image_url',
            'length_mm', 'width_mm', 'height_mm', 'weight_kg',
            'color', 'brand', 'volume_m3'
        ];

        foreach ($fields as $field) {
            if (isset($productData[$field]) && $productData[$field] !== null) {
                $updateData[$field] = $productData[$field];
            }
        }

        if (!empty($updateData)) {
            $material->update($updateData);
        }
    }

    private function findOrCreateCategory(string $categorySlug): ?MaterialCategory
    {
        if (!isset($this->categoryMapping[$categorySlug])) {
            Log::warning("Category mapping not found for: {$categorySlug}");
            return null;
        }

        $mapping = $this->categoryMapping[$categorySlug];
        $categoryName = $mapping['category_name'];

        // Ищем родительскую категорию
        $parentCategory = MaterialCategory::where('name', 'Стройматериалы OBI')->first();

        if (!$parentCategory) {
            $parentCategory = MaterialCategory::create([
                'name' => 'Стройматериалы OBI',
                'parent_id' => null,
            ]);
            Log::info("Created parent category: Стройматериалы OBI");
        }

        // Создаем или находим категорию
        $category = MaterialCategory::firstOrCreate(
            ['name' => $categoryName],
            ['parent_id' => $parentCategory->id]
        );

        Log::info("Category found/created: {$categoryName}");
        return $category;
    }

    private function mapCharacteristic(array &$details, string $key, string $value): void
    {
        $mapping = [
            'Бренд' => 'brand',
            'Основной цвет' => 'color',
            'Цвет' => 'color',
            'Длина' => 'length_mm',
            'Высота' => 'height_mm',
            'Ширина' => 'width_mm',
            'Толщина' => 'height_mm',
            'Вес' => 'weight_kg',
            'Вес брутто' => 'weight_kg',
            'Глубина' => 'height_mm',
        ];

        $field = $mapping[$key] ?? null;

        if (!$field) {
            return;
        }

        // Извлекаем числовое значение из строки
        preg_match('/(\d+[.,]?\d*)/', $value, $matches);
        $numericValue = $matches[1] ?? null;

        if ($numericValue) {
            $numericValue = str_replace(',', '.', $numericValue);

            // Для размеров в мм
            if (in_array($field, ['length_mm', 'width_mm', 'height_mm'])) {
                $details[$field] = (float) $numericValue;
            }
            // Для веса в кг
            elseif ($field === 'weight_kg') {
                $details[$field] = (float) $numericValue;
            }
        } else {
            // Для текстовых полей (бренд, цвет)
            $details[$field] = $value;
        }
    }

    private function calculateVolume(array $details): ?float
    {
        if ($details['length_mm'] && $details['width_mm'] && $details['height_mm']) {
            // Переводим мм в метры и вычисляем объем в м³
            $volume = ($details['length_mm'] / 1000) *
                ($details['width_mm'] / 1000) *
                ($details['height_mm'] / 1000);
            return round($volume, 4);
        }

        return null;
    }

    private function createConsumptionRates(array $materials, string $categorySlug): array
    {
        if (!isset($this->categoryMapping[$categorySlug])) {
            return [];
        }

        $mapping = $this->categoryMapping[$categorySlug];
        $workTypeName = $mapping['work_type'];
        $baseRate = $mapping['consumption_rate'];

        // Находим вид работ
        $workType = WorkType::where('name', $workTypeName)->first();
        if (!$workType) {
            Log::warning("Work type not found: {$workTypeName}");
            return [];
        }

        $createdRates = [];

        foreach ($materials as $material) {
            try {
                // Если передан массив, получаем объект Material
                if (is_array($material)) {
                    $material = Material::where('external_id', $material['external_id'])->first();
                    if (!$material) continue;
                }

                // Проверяем, существует ли уже норма расхода
                $existingRate = MaterialConsumptionRate::where([
                    'work_type_id' => $workType->id,
                    'material_id' => $material->id,
                ])->first();

                if ($existingRate) {
                    continue;
                }

                // Создаем норму расхода
                $rate = MaterialConsumptionRate::create([
                    'work_type_id' => $workType->id,
                    'material_id' => $material->id,
                    'consumption_rate' => $this->calculateConsumptionRate($material, $baseRate),
                ]);

                $createdRates[] = $rate;
                Log::info("Created consumption rate for: {$material->name}");

            } catch (\Exception $e) {
                Log::error("Error creating consumption rate: " . $e->getMessage());
            }
        }

        return $createdRates;
    }

    private function calculateConsumptionRate($material, float $baseRate): float
    {
        $name = strtolower($material->name);
        $unit = $material->unit;

        if ($unit === 'м²' || $unit === 'за М²') {
            return $baseRate;
        } elseif ($unit === 'шт') {
            return $baseRate * 0.1;
        } elseif (str_contains($name, 'краск') || str_contains($name, 'лак')) {
            return 0.15;
        } elseif (str_contains($name, 'штукатурк')) {
            return 1.7;
        }

        return $baseRate;
    }

    private function updatePrice(int $materialId, ?float $price): void
    {
        if ($price) {
            MaterialPrice::create([
                'material_id' => $materialId,
                'price' => $price,
                'price_date' => now()->toDateString(),
                'url' => null,
            ]);
        }
    }
}
