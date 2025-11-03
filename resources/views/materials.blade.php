<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Материалы — MaterialHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100">

@include('layouts.nav')

<div class="max-w-10xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
        🧱 Кирпич и блоки
    </h1>

    <!-- Сетка: Фильтр | Карточки | Калькулятор -->
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr_260px] gap-8">
        <!-- ✅ Фильтр — стиль как у калькулятора -->
        <aside class="bg-white p-6 shadow-md rounded-2xl border border-gray-200 space-y-4 h-fit top-6 self-start">
            <h2 class="text-lg font-semibold text-gray-900 text-center">Фильтры</h2>

            <!-- Название -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Название</label>
                <input type="text" id="filter-name"
                       class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
            </div>

            <!-- Бренд -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Бренд</label>
                <select id="filter-brand"
                        class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                    <option value="">Все</option>
                </select>
            </div>

            <!-- Цвет -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Цвет</label>
                <select id="filter-color"
                        class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                    <option value="">Любой</option>
                </select>
            </div>

            <!-- Цена -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Цена, ₽</label>
                <div class="flex gap-2 mt-1">
                    <input type="number" id="filter-price-min" placeholder="от"
                           class="w-1/2 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                    <input type="number" id="filter-price-max" placeholder="до"
                           class="w-1/2 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-700 font-medium">Поставщик</label>
                <select id="filter-supplier"
                        class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                    <option value="">Все поставщики</option>
                </select>
            </div>

            <!-- Кнопка -->
            <button id="apply-filters"
                    class="bg-orange-400 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                Применить
            </button>

        </aside>

        <!-- ✅ Карточки — контейнер для динамического контента -->
        <section>
            <div id="materials-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- Loading state -->
                <div id="loading-state" class="col-span-3 text-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto mb-4"></div>
                    <p class="text-gray-600">Загрузка материалов...</p>
                </div>
            </div>
        </section>

        <!-- ✅ Калькулятор кирпича -->
        <aside class="bg-white p-6 shadow-md rounded-2xl border border-gray-200 space-y-4 h-fit top-6 self-start">
            <h3 class="text-lg font-semibold text-gray-900 gap-2 text-center">Калькулятор</h3>

            <!-- Размер кирпича -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Размер кирпича</label>
                <select class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <option>250×120×65</option>
                    <option>250×120×88</option>
                    <option>230×110×65</option>
                </select>
            </div>

            <!-- Длина стен -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Длина стен</label>
                <div class="relative mt-1">
                    <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м</span>
                </div>
            </div>

            <!-- Высота стен -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Высота стен</label>
                <div class="relative mt-1">
                    <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м</span>
                </div>
            </div>

            <!-- Толщина стен -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Толщина стен</label>
                <select class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <option>0.5 кирпича</option>
                    <option>1 кирпич</option>
                    <option>1.5 кирпича</option>
                    <option>2 кирпича</option>
                </select>
            </div>

            <!-- Кладочная сетка -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Кладочная сетка</label>
                <div class="relative mt-1">
                    <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-10 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м²</span>
                </div>
            </div>

            <!-- Цена за штуку -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Цена за 1 шт</label>
                <div class="relative mt-1">
                    <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">₽</span>
                </div>
            </div>

            <!-- Пустотность -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Пустотность кирпича</label>
                <div class="relative mt-1">
                    <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">%</span>
                </div>
            </div>

            <!-- ✅ Кнопка -->
            <button class="bg-gray-800 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                Рассчитать
            </button>
        </aside>
    </div>
</div>

<script>
    // Global variables
    let allMaterials = [];
    let filteredMaterials = [];

    // Fetch materials from API and display them
    async function loadMaterials() {
        try {
            showLoading();

            // Use the correct API endpoint with /api/ prefix
            const baseUrl = window.location.origin;
            const response = await fetch(`${baseUrl}/api/materials`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            allMaterials = await response.json();
            console.log('Loaded materials:', allMaterials); // Debug log

            filteredMaterials = [...allMaterials];

            // Populate filter dropdowns
            populateFilters();

            // Display materials
            displayMaterials();

            hideLoading();
        } catch (error) {
            console.error('Error loading materials:', error);
            showError('Ошибка загрузки материалов: ' + error.message);
        }
    }

    function populateFilters() {
        const brandSelect = document.getElementById('filter-brand');
        const colorSelect = document.getElementById('filter-color');
        const supplierSelect = document.getElementById('filter-supplier');

        if (!brandSelect || !colorSelect || !supplierSelect) return;

        // Get unique brands, colors, and suppliers
        const brands = [...new Set(allMaterials.map(m => m.brand).filter(Boolean))];
        const colors = [...new Set(allMaterials.map(m => m.color).filter(Boolean))];
        const suppliers = [...new Set(allMaterials.map(m => m.supplier?.name).filter(Boolean))];

        // Populate brand filter
        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            brandSelect.appendChild(option);
        });

        // Populate color filter
        colors.forEach(color => {
            const option = document.createElement('option');
            option.value = color;
            option.textContent = color;
            colorSelect.appendChild(option);
        });

        // Populate supplier filter
        suppliers.forEach(supplier => {
            const option = document.createElement('option');
            option.value = supplier;
            option.textContent = supplier;
            supplierSelect.appendChild(option);
        });
    }

    function displayMaterials() {
        const container = document.getElementById('materials-container');
        if (!container) return;

        container.innerHTML = '';

        if (filteredMaterials.length === 0) {
            container.innerHTML = `
                <div class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Материалы не найдены</h3>
                    <p class="text-gray-500">Попробуйте изменить параметры фильтра</p>
                </div>
            `;
            return;
        }

        filteredMaterials.forEach(material => {
            const card = createMaterialCard(material);
            container.appendChild(card);
        });
    }

    function createMaterialCard(material) {
        const card = document.createElement('div');
        card.className = 'bg-white rounded-xl border border-gray-200 shadow hover:shadow-xl transition flex flex-col overflow-hidden w-full min-h-[430px]';

        // Format dimensions
        const dimensions = material.length_mm && material.width_mm && material.height_mm
            ? `${material.length_mm}×${material.width_mm}×${material.height_mm}`
            : null;

        const price = material.latest_price ? material.latest_price.price :
            material.prices && material.prices.length > 0 ? material.prices[0].price : null;

        // Get supplier name
        const supplierName = material.supplier ? material.supplier.name : 'Не указан';

        card.innerHTML = `
            <!-- Фото -->
            <div class="h-40 bg-gray-100 flex items-center justify-center p-4">
                ${material.image_url
            ? `<img src="${material.image_url}" alt="${material.name}" class="max-h-full object-contain" onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\\"text-gray-400 text-center\\"><svg class=\\"w-12 h-12 mx-auto mb-2\\" fill=\\"none\\" stroke=\\"currentColor\\" viewBox=\\"0 0 24 24\\"><path stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\" stroke-width=\\"2\\" d=\\"M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\\"></path></svg><span class=\\"text-sm\\">No image</span></div>';"`
            : `<div class="text-gray-400 text-center">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm">No image</span>
                    </div>`
        }
            </div>

            <!-- Контент -->
            <div class="p-4 text-sm text-gray-700 flex flex-col gap-1 flex-grow">
                <h3 class="font-semibold text-lg text-gray-900 h-12 overflow-hidden">
                    ${material.name}
                </h3>
                <p class="text-gray-500">${material.article || ''}</p>

                ${dimensions ? `<div class="flex justify-between"><span>Размер:</span><span>${dimensions}</span></div>` : ''}
                ${material.weight_kg ? `<div class="flex justify-between"><span>Вес:</span><span>${material.weight_kg} кг</span></div>` : ''}
                ${material.color ? `<div class="flex justify-between"><span>Цвет:</span><span>${material.color}</span></div>` : ''}
                ${material.brand ? `<div class="flex justify-between"><span>Бренд:</span><span>${material.brand}</span></div>` : ''}
                <div class="flex justify-between"><span>Поставщик:</span><span>${supplierName}</span></div>

                <p class="text-xl font-bold text-gray-900 mt-auto">
                    ${price ? `${formatPrice(price)}₽ /${material.unit}` : 'Цена не указана'}
                </p>

                <!-- Количество -->
                <div x-data="{ qty: 1 }" class="mt-3 mx-auto border border-gray-300 rounded-xl px-4 py-2 w-full flex items-center justify-between gap-3">
                    <button @click="qty = Math.max(1, qty - 1)" class="text-2xl leading-none text-gray-600 hover:text-black">–</button>
                    <input type="number" min="1" x-model="qty" class="w-12 text-center outline-none bg-transparent text-lg font-medium border-0 focus:ring-0">
                    <span class="text-sm text-gray-700">${material.unit}</span>
                    <button @click="qty++" class="text-2xl leading-none text-gray-600 hover:text-black">+</button>
                </div>

                <div class="flex gap-2 mt-3">
                    <button class="bg-orange-500 text-white py-2 px-3 rounded-lg hover:bg-orange-600 transition flex-1 text-sm">
                        В смету
                    </button>
                    <button class="bg-gray-800 text-white py-2 px-3 rounded-lg hover:bg-gray-600 transition flex-1 text-sm border border-gray-300">
                        Рассчитать
                    </button>
                </div>
            </div>
        `;

        return card;
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('ru-RU').format(price);
    }

    function applyFilters() {
        const nameFilter = document.getElementById('filter-name')?.value.toLowerCase() || '';
        const brandFilter = document.getElementById('filter-brand')?.value || '';
        const colorFilter = document.getElementById('filter-color')?.value || '';
        const supplierFilter = document.getElementById('filter-supplier')?.value || '';
        const priceMin = document.getElementById('filter-price-min')?.value || '';
        const priceMax = document.getElementById('filter-price-max')?.value || '';

        filteredMaterials = allMaterials.filter(material => {
            // Name filter
            if (nameFilter && !material.name.toLowerCase().includes(nameFilter)) {
                return false;
            }

            // Brand filter
            if (brandFilter && material.brand !== brandFilter) {
                return false;
            }

            // Color filter
            if (colorFilter && material.color !== colorFilter) {
                return false;
            }

            // Supplier filter
            if (supplierFilter && material.supplier?.name !== supplierFilter) {
                return false;
            }

            // Price filter
            const price = material.latest_price ? material.latest_price.price :
                material.prices && material.prices.length > 0 ? material.prices[0].price : 0;
            if (priceMin && price < parseFloat(priceMin)) {
                return false;
            }
            if (priceMax && price > parseFloat(priceMax)) {
                return false;
            }

            return true;
        });

        displayMaterials();
    }

    function showLoading() {
        const loadingElement = document.getElementById('loading-state');
        if (loadingElement) {
            loadingElement.style.display = 'block';
        }
    }

    function hideLoading() {
        const loadingElement = document.getElementById('loading-state');
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
    }

    function showError(message) {
        const container = document.getElementById('materials-container');
        if (!container) return;

        container.innerHTML = `
            <div class="col-span-3 text-center py-12">
                <div class="text-red-500 text-lg mb-2">❌</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Ошибка</h3>
                <p class="text-gray-500">${message}</p>
                <button onclick="loadMaterials()" class="mt-4 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                    Попробовать снова
                </button>
            </div>
        `;
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we're on the materials page
        const materialsContainer = document.getElementById('materials-container');
        if (materialsContainer) {
            loadMaterials();

            // Filter button
            const applyButton = document.getElementById('apply-filters');
            if (applyButton) {
                applyButton.addEventListener('click', applyFilters);
            }

            // Enter key in filter inputs
            const nameFilter = document.getElementById('filter-name');
            if (nameFilter) {
                nameFilter.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') applyFilters();
                });
            }
        }
    });
</script>
</body>
</html>
