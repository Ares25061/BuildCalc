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
                            {--limit=50 : Товаров на категорию}
                            {--pages=2 : Страниц на категорию}
                            {--with-rates : Создать нормы расхода}
                            {--skip-existing : Пропустить категории с существующими материалами}
                            {--categories= : Конкретные категории (через запятую)}
                            {--scan : Принудительное сканирование категорий с OBI}
                            {--update-images : Обновить картинки для существующих категорий}';

    protected $description = 'Автоматический парсинг всех категорий OBI';

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
        $this->info('🚀 Запуск автоматического парсинга всех категорий OBI...');

        $parser = new ObiParserService();
        $limit = (int)$this->option('limit');
        $pages = (int)$this->option('pages');
        $withRates = $this->option('with-rates');
        $skipExisting = $this->option('skip-existing');
        $specificCategories = $this->option('categories');
        $forceScan = $this->option('scan');
        $updateImages = $this->option('update-images');

        $this->showConfig($limit, $pages, $withRates, $skipExisting);

        // Обновление картинок для существующих категорий
        if ($updateImages) {
            $this->updateExistingCategoriesImages();
            return 0;
        }

        // Получаем категории для парсинга (сканируем с сайта OBI)
        $categoriesToParse = $this->getCategoriesToParse($specificCategories, $skipExisting, $forceScan);

        if (empty($categoriesToParse)) {
            $this->error('❌ Не найдено категорий для парсинга');
            return 1;
        }

        $this->info("\n📋 Категорий для парсинга: " . count($categoriesToParse));

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
        $this->info("⚙️  Конфигурация:");
        $this->info("   📊 Товаров на категорию: {$limit}");
        $this->info("   📄 Страниц на категорию: {$pages}");
        $this->info("   📏 С нормами расхода: " . ($withRates ? 'Да' : 'Нет'));
        $this->info("   ⏭️ Пропускать существующие: " . ($skipExisting ? 'Да' : 'Нет'));
    }
    private function getManualCategories(): array
    {
        return [
            // Основные категории стройматериалов
            [
                'name' => 'Фасадные материалы',
                'slug' => 'fasadnye-materialy',
                'image_url' => 'https://media.obi.ru/media/catalog/category/fasadnye_materialy.png',
                'url' => 'https://obi.ru/strojmaterialy/fasadnye-materialy'
            ],
            [
                'name' => 'Кровля',
                'slug' => 'krovlja',
                'image_url' => 'https://media.obi.ru/media/catalog/category/krovlja.png',
                'url' => 'https://obi.ru/strojmaterialy/krovlja'
            ],
            [
                'name' => 'Водосток',
                'slug' => 'vodostok',
                'image_url' => 'https://media.obi.ru/media/catalog/category/vodostok.png',
                'url' => 'https://obi.ru/strojmaterialy/vodostok'
            ],
            [
                'name' => 'Наружная канализация',
                'slug' => 'naruzhnaja-kanalizacija',
                'image_url' => 'https://media.obi.ru/media/catalog/category/kanalizacija_naruzhnaja.png',
                'url' => 'https://obi.ru/strojmaterialy/naruzhnaja-kanalizacija'
            ],
            [
                'name' => 'Теплоизоляция',
                'slug' => 'teploizoljacija',
                'image_url' => 'https://media.obi.ru/media/catalog/category/utepliteli.png',
                'url' => 'https://obi.ru/strojmaterialy/teploizoljacija'
            ],
            [
                'name' => 'Шумоизоляция',
                'slug' => 'shumoizoljacija',
                'image_url' => 'https://media.obi.ru/media/catalog/category/shumoizoljacija.png',
                'url' => 'https://obi.ru/strojmaterialy/shumoizoljacija'
            ],
            [
                'name' => 'Гидроизоляция',
                'slug' => 'gidroizoljacija',
                'image_url' => 'https://media.obi.ru/media/catalog/category/gidroizoljacija.png',
                'url' => 'https://obi.ru/strojmaterialy/gidroizoljacija'
            ],
            [
                'name' => 'Пароизоляция',
                'slug' => 'paroizoljacija',
                'image_url' => 'https://media.obi.ru/media/catalog/category/paroizoljacija.png',
                'url' => 'https://obi.ru/strojmaterialy/paroizoljacija'
            ],
            [
                'name' => 'Металлопрокат',
                'slug' => 'metalloprokat',
                'image_url' => 'https://media.obi.ru/media/catalog/category/metalloprokat.png',
                'url' => 'https://obi.ru/strojmaterialy/metalloprokat'
            ],
            [
                'name' => 'Сухие строительные смеси',
                'slug' => 'suhie-stroitelnye-smesi',
                'image_url' => 'https://media.obi.ru/media/catalog/category/suhie_stroitelnye_smesi.png',
                'url' => 'https://obi.ru/strojmaterialy/suhie-stroitelnye-smesi'
            ],
            [
                'name' => 'Блоки строительные',
                'slug' => 'bloki-stroitelnye',
                'image_url' => 'https://media.obi.ru/media/catalog/category/bloki_stroitelnye.png',
                'url' => 'https://obi.ru/strojmaterialy/bloki-stroitelnye'
            ],
            [
                'name' => 'Листовые материалы',
                'slug' => 'listovye-materialy',
                'image_url' => 'https://media.obi.ru/media/catalog/category/listovye_materialy.png',
                'url' => 'https://obi.ru/strojmaterialy/listovye-materialy'
            ],
            [
                'name' => 'Строительное оборудование',
                'slug' => 'stroitelnoe-oborudovanie',
                'image_url' => 'https://media.obi.ru/media/catalog/category/stroitelnoe_oborudovanie.png',
                'url' => 'https://obi.ru/strojmaterialy/stroitelnoe-oborudovanie'
            ],
            [
                'name' => 'Строительные расходные материалы',
                'slug' => 'stroitelnye-rashodnye-materialy',
                'image_url' => 'https://media.obi.ru/media/catalog/category/stroitelnye_rashodnye_materialy.png',
                'url' => 'https://obi.ru/strojmaterialy/stroitelnye-rashodnye-materialy'
            ],
            [
                'name' => 'Подвесные потолки',
                'slug' => 'podvesnye-potolki',
                'image_url' => 'https://media.obi.ru/media/catalog/category/podvesnye_potolki.png',
                'url' => 'https://obi.ru/strojmaterialy/podvesnye-potolki'
            ],
        ];
    }
    private function getCategoriesToParse(?string $specificCategories, bool $skipExisting, bool $forceScan): array
    {
        if ($specificCategories) {
            return $this->getSpecificCategories($specificCategories);
        }

        // Сначала сканируем стройматериалы с сайта (чтобы получить актуальные картинки)
        $scannedCategories = $this->getCategoriesFromObi($skipExisting, $forceScan);

        // Добавляем новые категории из ручного списка
        $newCategories = $this->getNewCategories();

        // Объединяем категории
        $allCategories = array_merge($scannedCategories, $newCategories);

        // Создаем все категории в БД
        $this->createCategoriesInDb($allCategories);

        // Фильтруем существующие если нужно
        if ($skipExisting) {
            $allCategories = $this->filterExistingCategories($allCategories);
        }

        $this->info("📋 Всего категорий для парсинга: " . count($allCategories));
        return $allCategories;
    }
    private function getNewCategories(): array
    {
        return [
            // НОВЫЕ КАТЕГОРИИ - КРАСКИ И ПОКРЫТИЯ
            [
                'name' => 'Краски для внутренних работ',
                'slug' => 'kraski-dlja-vnutrennih-rabot', // исправлен slug
                'image_url' => 'https://media.obi.ru/media/catalog/category/_-32.png',
                'url' => 'https://obi.ru/lakokrasochnye-materialy/kraski-dlja-vnutrennih-rabot'
            ],
            [
                'name' => 'Краски для наружных работ',
                'slug' => 'kraski-dlja-naruzhnyh-rabot',
                'image_url' => 'https://media.obi.ru/media/catalog/category/_-33.png',
                'url' => 'https://obi.ru/lakokrasochnye-materialy/kraski-dlja-naruzhnyh-rabot'
            ],
            [
                'name' => 'Эмали',
                'slug' => 'jemali',
                'image_url' => 'https://media.obi.ru/media/catalog/category/_-31.png',
                'url' => 'https://obi.ru/lakokrasochnye-materialy/jemali' // исправлен URL
            ],
            [
                'name' => 'Покрытия для дерева',
                'slug' => 'pokrytija-dlja-dereva',
                'image_url' => 'https://media.obi.ru/media/catalog/category/_-30.png',
                'url' => 'https://obi.ru/lakokrasochnye-materialy/pokrytija-dlja-dereva' // исправлен URL
            ],

            // НОВЫЕ КАТЕГОРИИ - ОБОИ
            [
                'name' => 'Декоративные обои',
                'slug' => 'dekorativnye-oboi',
                'image_url' => 'https://media.obi.ru/media/catalog/category/file_236_2.png',
                'url' => 'https://obi.ru/dekor/oboi/dekorativnye-oboi'
            ],
            [
                'name' => 'Обои под покраску',
                'slug' => 'oboi-pod-pokrasku',
                'image_url' => 'https://media.obi.ru/media/catalog/category/file_237.png',
                'url' => 'https://obi.ru/dekor/oboi/oboi-pod-pokrasku'
            ],
            [
                'name' => 'Фотообои',
                'slug' => 'fotooboi',
                'image_url' => 'https://media.obi.ru/media/catalog/category/file_236_4.png',
                'url' => 'https://obi.ru/dekor/oboi/fotooboi'
            ],

            // НОВЫЕ КАТЕГОРИИ - ПЛИТКА
            [
                'name' => 'Плитка',
                'slug' => 'plitka',
                'image_url' => 'https://media.obi.ru/media/catalog/category/_-11_3.png',
                'url' => 'https://obi.ru/plitka'
            ],
        ];
    }

    private function getSpecificCategories(string $categoriesList): array
    {
        $categorySlugs = array_map('trim', explode(',', $categoriesList));
        $categories = [];

        foreach ($categorySlugs as $slug) {
            // Получаем полную информацию о категории из ручного списка
            $categoryInfo = $this->findCategoryInManualList($slug);

            if ($categoryInfo) {
                $categories[] = $categoryInfo;
            } else {
                // Если категории нет в ручном списке, создаем базовую структуру
                $categories[] = [
                    'name' => $this->slugToName($slug),
                    'slug' => $slug,
                    'image_url' => null,
                    'url' => $this->getCategoryUrl($slug)
                ];
            }
        }

        $this->info("🎯 Конкретные категории: " . implode(', ', $categorySlugs));
        return $categories;
    }

    private function findCategoryInManualList(string $slug): ?array
    {
        $manualCategories = $this->getManualCategories();

        foreach ($manualCategories as $category) {
            if ($category['slug'] === $slug) {
                return $category;
            }
        }

        $newCategories = $this->getNewCategories();

        foreach ($newCategories as $category) {
            if ($category['slug'] === $slug) {
                return $category;
            }
        }

        return null;
    }

    private function getCategoryUrl(string $slug): string
    {
        // Базовый URL для категорий
        $urls = [
            'kraski-dlja-vnutrennih-rabot' => 'https://obi.ru/lakokrasochnye-materialy/kraski-dlja-vnutrennih-rabot',
            'kraski-dlja-naruzhnyh-rabot' => 'https://obi.ru/lakokrasochnye-materialy/kraski-dlja-naruzhnyh-rabot',
            'jemali' => 'https://obi.ru/lakokrasochnye-materialy/jemali',
            'pokrytija-dlja-dereva' => 'https://obi.ru/lakokrasochnye-materialy/pokrytija-dlja-dereva',
            'dekorativnye-oboi' => 'https://obi.ru/dekor/oboi/dekorativnye-oboi',
            'oboi-pod-pokrasku' => 'https://obi.ru/dekor/oboi/oboi-pod-pokrasku',
            'fotooboi' => 'https://obi.ru/dekor/oboi/fotooboi',
            'plitka' => 'https://obi.ru/plitka',
        ];

        return $urls[$slug] ?? 'https://obi.ru/strojmaterialy/' . $slug;
    }

    private function getCategoriesFromObi(bool $skipExisting, bool $forceScan): array
    {
        $this->info("\n🔍 Сканирование категорий OBI...");

        try {
            $url = 'https://obi.ru/strojmaterialy';
            $response = $this->client->get($url);
            $html = (string)$response->getBody();
            $document = new Document($html);

            $categoryData = [];
            $processedSlugs = [];

            // Ищем категории на странице
            $categories = $document->find('a[href*="/strojmaterialy/"]');

            foreach ($categories as $category) {
                $href = $category->getAttribute('href');

                // Фильтруем только категории стройматериалов
                if (strpos($href, '/strojmaterialy/') === false ||
                    strpos($href, '?') !== false ||
                    in_array($href, ['/strojmaterialy/', '/strojmaterialy'])) {
                    continue;
                }

                // Извлекаем slug из URL
                $slug = str_replace('/strojmaterialy/', '', $href);
                $slug = rtrim($slug, '/');

                // Пропускаем дубликаты
                if (in_array($slug, $processedSlugs) || empty($slug)) {
                    continue;
                }

                // Пробуем разные селекторы для названия
                $name = $this->extractCategoryName($category);

                if (!$name || strlen($name) < 2) {
                    continue;
                }

                // Фильтруем ненужные категории
                if (in_array($name, ['Стройматериалы', 'Все товары', 'Акции', 'Новинки', 'Распродажа'])) {
                    continue;
                }

                // Извлекаем картинку категории
                $imageUrl = $this->extractCategoryImage($category);

                $categoryData[] = [
                    'name' => $name,
                    'slug' => $slug,
                    'image_url' => $imageUrl,
                    'url' => 'https://obi.ru' . $href
                ];

                $processedSlugs[] = $slug;
            }

            $this->info("✅ Найдено " . count($categoryData) . " категорий стройматериалов на OBI");

            // Если не нашли категории, используем ручной список как резерв
            if (empty($categoryData)) {
                $this->warn("⚠️ Сканирование не нашло категории, используем ручной список");
                $categoryData = $this->getManualCategories();
            }

            return $categoryData;

        } catch (\Exception $e) {
            $this->error("❌ Ошибка сканирования категорий: " . $e->getMessage());
            $this->warn("⚠️ Используем ручной список категорий");
            return $this->getManualCategories();
        }
    }

    private function scanSectionCategories(string $sectionUrl, string $sectionName): array
    {
        $url = 'https://obi.ru' . $sectionUrl;
        $response = $this->client->get($url);
        $html = (string)$response->getBody();
        $document = new Document($html);

        $categoryData = [];
        $processedSlugs = [];

        // Ищем ссылки на категории в этом разделе
        $categoryLinks = $document->find('a[href*="' . $sectionUrl . '/"]');

        foreach ($categoryLinks as $category) {
            $href = $category->getAttribute('href');

            // Пропускаем ссылки на ту же страницу или с параметрами
            if ($href === $sectionUrl || strpos($href, '?') !== false) {
                continue;
            }

            // Извлекаем slug из URL
            $slug = str_replace($sectionUrl . '/', '', $href);
            $slug = rtrim($slug, '/');

            // Пропускаем дубликаты и пустые slug'и
            if (in_array($slug, $processedSlugs) || empty($slug)) {
                continue;
            }

            // Пробуем извлечь название категории
            $name = $this->extractCategoryName($category);

            if (!$name || strlen($name) < 2) {
                continue;
            }

            // Фильтруем ненужные названия
            if (in_array($name, ['Стройматериалы', 'Все товары', 'Акции', 'Новинки', 'Распродажа', 'Обои', 'Плитка', 'Лакокрасочные материалы'])) {
                continue;
            }

            // Извлекаем картинку категории
            $imageUrl = $this->extractCategoryImage($category);

            $categoryData[] = [
                'name' => $name,
                'slug' => $slug,
                'image_url' => $imageUrl,
                'url' => 'https://obi.ru' . $href
            ];

            $processedSlugs[] = $slug;
        }

        $this->info("   📁 В разделе {$sectionName} найдено: " . count($categoryData) . " категорий");
        return $categoryData;
    }

    private function extractCategoryName($categoryElement): ?string
    {
        // Пробуем разные селекторы для названия категории
        $selectors = [
            'span._17tb-',
            '.category-name',
            '.kn7A0 span',
            'span',
            'div'
        ];

        foreach ($selectors as $selector) {
            $nameElement = $categoryElement->first($selector);
            if ($nameElement) {
                $name = trim($nameElement->text());
                if (!empty($name) && strlen($name) > 1) {
                    return $name;
                }
            }
        }

        return null;
    }

    private function extractCategoryImage($categoryElement): ?string
    {
        try {
            // Основной селектор для картинок категорий
            $imageElement = $categoryElement->first('img._1Z94x');
            if ($imageElement) {
                $src = $imageElement->getAttribute('src');
                if ($src && $this->isValidImageUrl($src)) {
                    return $this->normalizeImageUrl($src);
                }
            }

            // Альтернативные селекторы
            $alternativeSelectors = [
                '.Image img',
                '.category-image img',
                'img[loading="lazy"]',
                'picture img',
                '.z740A img'
            ];

            foreach ($alternativeSelectors as $selector) {
                $img = $categoryElement->first($selector);
                if ($img) {
                    $src = $img->getAttribute('src') ?:
                        $img->getAttribute('data-src') ?:
                            $img->getAttribute('data-lazy-src');

                    if ($src && $this->isValidImageUrl($src)) {
                        return $this->normalizeImageUrl($src);
                    }
                }
            }

            return null;

        } catch (\Exception $e) {
            return null;
        }
    }

    private function isValidImageUrl(?string $url): bool
    {
        if (!$url) return false;

        // Проверяем, что это URL картинки
        $imageExtensions = ['.jpg', '.jpeg', '.png', '.webp', '.gif', '.avif'];
        $url = strtolower($url);

        foreach ($imageExtensions as $ext) {
            if (str_contains($url, $ext)) {
                return true;
            }
        }

        // Проверяем паттерны OBI для картинок
        $obiPatterns = ['/obi.ru\/img/', '/obi.ru\/pictures/', '/images.obi.ru/', '/media.obi.ru/'];
        foreach ($obiPatterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }

    private function normalizeImageUrl(string $url): string
    {
        // Преобразуем относительные URL в абсолютные
        if (str_starts_with($url, '//')) {
            return 'https:' . $url;
        }

        if (str_starts_with($url, '/')) {
            return 'https://obi.ru' . $url;
        }

        return $url;
    }

    private function showScannedCategories(array $categories): void
    {
        $this->info("\n📋 Найденные категории OBI:");

        $tableData = [];
        foreach ($categories as $category) {
            $hasImage = $category['image_url'] ? "✅" : "❌";
            $tableData[] = [
                $category['name'],
                $category['slug'],
                $hasImage,
                $category['image_url'] ? Str::limit($category['image_url'], 40) : 'Нет картинки'
            ];
        }

        $this->table(
            ['Название', 'Slug', 'Картинка', 'URL картинки'],
            $tableData
        );

        // Показываем статистику по картинкам
        $categoriesWithImages = count(array_filter($categories, fn($cat) => !empty($cat['image_url'])));
        $this->info("🖼️  Категорий с картинками: {$categoriesWithImages}/" . count($categories));
    }

    private function createCategoriesInDb(array $categories): void
    {
        $this->info("\n💾 Создание категорий в базе данных...");

        // Создаем родительские категории
        $parentCategories = [
            'Стройматериалы OBI' => null,
            'Отделочные материалы OBI' => null,
            'Лакокрасочные материалы OBI' => null,
            'Обои OBI' => null,
            'Плитка OBI' => null,
        ];

        foreach ($parentCategories as $parentName => $null) {
            $parentCategories[$parentName] = MaterialCategory::firstOrCreate(
                ['name' => $parentName],
                ['parent_id' => null]
            );
        }

        $createdCount = 0;
        $existingCount = 0;
        $imagesCount = 0;

        foreach ($categories as $category) {
            $existingCategory = MaterialCategory::where('name', $category['name'])->first();

            // Определяем родительскую категорию
            $parentCategory = $this->determineParentCategory($category, $parentCategories);

            if (!$existingCategory) {
                MaterialCategory::create([
                    'name' => $category['name'],
                    'parent_id' => $parentCategory ? $parentCategory->id : $parentCategories['Стройматериалы OBI']->id,
                    'image_url' => $category['image_url']
                ]);
                $createdCount++;
                if ($category['image_url']) $imagesCount++;
            } else {
                // Обновляем картинку у существующей категории если её нет
                if (!$existingCategory->image_url && $category['image_url']) {
                    $existingCategory->update(['image_url' => $category['image_url']]);
                    $imagesCount++;
                }
                $existingCount++;
            }
        }

        $this->info("✅ Создано категорий: {$createdCount}, существовало: {$existingCount}, с картинками: {$imagesCount}");
    }

    private function determineParentCategory(array $category, array $parentCategories): ?MaterialCategory
    {
        $name = strtolower($category['name']);
        $slug = strtolower($category['slug']);

        // Лакокрасочные материалы
        if (str_contains($slug, 'krask') || str_contains($slug, 'lakokras') ||
            str_contains($name, 'краск') || str_contains($name, 'лакокрас')) {
            return $parentCategories['Лакокрасочные материалы OBI'];
        }

        // Обои
        if (str_contains($slug, 'oboi') || str_contains($slug, 'fotooboi') ||
            str_contains($name, 'обои') || str_contains($name, 'фотообои')) {
            return $parentCategories['Обои OBI'];
        }

        // Плитка
        if (str_contains($slug, 'plitka') || str_contains($slug, 'keramichesk') ||
            str_contains($name, 'плитка') || str_contains($name, 'керамич')) {
            return $parentCategories['Плитка OBI'];
        }

        // Отделочные материалы (для остальных отделочных категорий)
        if (str_contains($slug, 'dekor') || str_contains($name, 'декор') ||
            str_contains($name, 'отдел')) {
            return $parentCategories['Отделочные материалы OBI'];
        }

        // По умолчанию - стройматериалы
        return $parentCategories['Стройматериалы OBI'];
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
                $this->info("⏭️ Пропускаем {$category['name']} - уже есть {$materialCount} материалов");
            }
        }

        $this->info("📊 После фильтрации: " . count($filtered) . " категорий для парсинга");
        return $filtered;
    }

    private function slugToName(string $slug): string
    {
        // Базовый маппинг для специфических случаев
        $mapping = [
            'fasadnye-materialy' => 'Фасадные материалы',
            'kraski' => 'Краски',
            'plitka' => 'Плитка',
            'krovlja' => 'Кровля',
            'lesomaterialy' => 'Лесоматериалы',
            'suhie-smesi' => 'Сухие смеси',
        ];

        return $mapping[$slug] ?? Str::title(str_replace('-', ' ', $slug));
    }

    private function parseCategory(ObiParserService $parser, array $category, int $limit, int $pages, bool $withRates, array &$totalResults): void
    {
        $this->info("\n" . str_repeat('=', 60));
        $this->info("🔄 Парсинг: {$category['name']} ({$category['slug']})");

        // Проверяем наличие картинки безопасно
        if (isset($category['image_url']) && $category['image_url']) {
            $this->info("   🖼️  Есть картинка категории");
        } else {
            $this->info("   ❌ Нет картинки категории");
        }

        $this->info(str_repeat('=', 60));

        try {
            // Парсим категорию
            $allPages = $pages > 1;
            $products = $parser->parseCategory($category['slug'], $limit, $allPages);

            if (empty($products)) {
                $this->error("❌ Не найдено товаров в категории {$category['name']}");
                $totalResults['failed']++;
                return;
            }

            $this->info("✅ Найдено " . count($products) . " товаров");

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
            $this->error("❌ Ошибка парсинга {$category['name']}: " . $e->getMessage());
            $totalResults['failed']++;
        }
    }

    private function showCategoryResults(array $results, array $products): void
    {
        $this->info("📊 Результаты по категории:");
        $this->info("   📦 Сохранено материалов: " . count($results['materials']));

        if ($results['category']) {
            $this->info("   📁 Категория: " . $results['category']->name);
        }

        if (!empty($results['consumption_rates'])) {
            $this->info("   📏 Норм расхода: " . count($results['consumption_rates']));
        }

        // Показываем несколько примеров
        if (count($products) > 0) {
            $sampleCount = min(3, count($products));
            $this->info("   📋 Примеры товаров:");

            for ($i = 0; $i < $sampleCount; $i++) {
                $product = $products[$i];
                $this->info("      • " . Str::limit($product['name'], 50) . " - " . ($product['price'] ?? 'N/A') . ' ₽');
            }
        }
    }

    private function updateExistingCategoriesImages(): void
    {
        $this->info("\n🔄 Обновление картинок для существующих категорий...");

        $categories = MaterialCategory::whereNull('image_url')
            ->where('name', '!=', 'Стройматериалы OBI')
            ->get();

        $this->info("📋 Найдено категорий без картинок: " . $categories->count());

        $updatedCount = 0;

        foreach ($categories as $category) {
            try {
                $this->info("\n🔍 Поиск картинки для: {$category->name}");

                // Сначала пробуем найти картинку в ручном списке
                $manualImageUrl = $this->findCategoryImageInManualList($category->name);

                if ($manualImageUrl) {
                    $category->update(['image_url' => $manualImageUrl]);
                    $this->info("✅ Обновлена картинка из ручного списка для: {$category->name}");
                    $updatedCount++;
                    continue;
                }

                // Если нет в ручном списке, пробуем найти на сайте
                $imageUrl = $this->findCategoryImageByName($category->name);

                if ($imageUrl) {
                    $category->update(['image_url' => $imageUrl]);
                    $this->info("✅ Обновлена картинка с сайта для: {$category->name}");
                    $updatedCount++;
                } else {
                    $this->warn("⚠️ Картинка не найдена для: {$category->name}");
                }

                sleep(1); // Пауза между запросами

            } catch (\Exception $e) {
                $this->warn("⚠️ Не удалось обновить картинку для {$category->name}: " . $e->getMessage());
            }
        }

        $this->info("\n🎯 Обновлено картинок для {$updatedCount} категорий");
    }

    private function findCategoryImageInManualList(string $categoryName): ?string
    {
        $manualCategories = array_merge($this->getManualCategories(), $this->getNewCategories());

        foreach ($manualCategories as $category) {
            if ($category['name'] === $categoryName && !empty($category['image_url'])) {
                return $category['image_url'];
            }
        }

        return null;
    }

    private function findCategoryImageByName(string $categoryName): ?string
    {
        try {
            // Пробуем найти категорию по имени через поиск
            $searchUrl = 'https://obi.ru/search?q=' . urlencode($categoryName);
            $response = $this->client->get($searchUrl);
            $html = (string)$response->getBody();
            $document = new Document($html);

            // Ищем категории в результатах поиска
            $categoryLinks = $document->find('a[href*="/strojmaterialy/"]');

            foreach ($categoryLinks as $link) {
                $nameElement = $link->first('span._17tb-') ?: $link->first('.category-name');
                if ($nameElement && trim($nameElement->text()) === $categoryName) {
                    return $this->extractCategoryImage($link);
                }
            }

            return null;

        } catch (\Exception $e) {
            $this->warn("   ⚠️ Ошибка поиска картинки по имени: " . $e->getMessage());
            return null;
        }
    }

    private function showFinalResults(array $totalResults): void
    {
        $this->info("\n" . str_repeat('⭐', 60));
        $this->info("🎊 АВТОМАТИЧЕСКИЙ ПАРСИНГ ЗАВЕРШЕН!");
        $this->info(str_repeat('⭐', 60));

        $this->info("📈 Итоговые результаты:");
        $this->info("   ✅ Обработано категорий: {$totalResults['categories']}");
        $this->info("   📦 Всего сохранено материалов: {$totalResults['materials']}");
        $this->info("   📏 Создано норм расхода: {$totalResults['rates']}");
        $this->info("   ❌ Неудачных категорий: {$totalResults['failed']}");

        $this->info("\n💾 В базе данных сейчас:");
        $this->info("   📁 Категорий: " . MaterialCategory::count());
        $this->info("   📦 Материалов: " . Material::count());
        $this->info("   💰 Цен: " . \App\Models\MaterialPrice::count());
        $this->info("   📏 Норм расхода: " . \App\Models\MaterialConsumptionRate::count());

        $this->info("\n🎯 Следующие шаги:");
        $this->info("   • Запустить: php artisan parse:all-obi --with-rates --limit=100");
        $this->info("   • Запустить: php artisan parse:all-obi --skip-existing");
        $this->info("   • Принудительное сканирование: php artisan parse:all-obi --scan");
        $this->info("   • Обновить картинки: php artisan parse:all-obi --update-images");
    }
}
