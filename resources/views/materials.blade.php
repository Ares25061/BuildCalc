<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} — MaterialHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100">

@include('layouts.nav')

<div class="max-w-10xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
        {{ $category->name }}
    </h1>

    <!-- Сетка: Фильтр | Карточки | Калькулятор -->
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr_260px] gap-8">
        <!-- ✅ Фильтр -->
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

        <!-- ✅ Карточки -->
        <section>
            <!-- Выбор проекта для добавления материалов -->
            <div id="project-selector" class="mb-6 bg-white p-4 rounded-2xl border border-gray-200 shadow hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Добавить материал в смету</h3>
                        <p class="text-sm text-gray-600">Выберите проект для добавления материалов</p>
                    </div>
                    <button onclick="hideProjectSelector()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mt-4">
                    <select id="project-select" class="w-full border border-gray-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500">
                        <option value="">Выберите проект...</option>
                    </select>
                    <div class="mt-3 flex gap-3">
                        <button onclick="addToSelectedProject()" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition flex-1">
                            Добавить в смету
                        </button>
                        <a href="/project/create" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-center flex items-center justify-center">
                            Новый проект
                        </a>
                    </div>
                </div>
            </div>

            <div id="materials-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                <!-- Loading state -->
                <div id="loading-state" class="col-span-3 text-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto mb-4"></div>
                    <p class="text-gray-600">Загрузка материалов...</p>
                </div>
            </div>
        </section>

        <!-- ✅ Калькулятор -->
        <aside class="bg-white p-6 shadow-md rounded-2xl border border-gray-200 space-y-4 h-fit top-6 self-start" >
            <h3 class="text-lg font-semibold text-gray-900 gap-2 text-center">Калькулятор</h3>

            <!-- Динамический калькулятор в зависимости от категории -->
            @if(str_contains(strtolower($category->name), 'кирпич') || str_contains(strtolower($category->name), 'блок'))
                <!-- Калькулятор кирпича -->
                <div>
                    <label class="text-sm text-gray-700 font-medium">Размер кирпича</label>
                    <select class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <option>250×120×65</option>
                        <option>250×120×88</option>
                        <option>230×110×65</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">Длина стен</label>
                    <div class="relative mt-1">
                        <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м</span>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">Высота стен</label>
                    <div class="relative mt-1">
                        <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м</span>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">Толщина стен</label>
                    <select class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <option>0.5 кирпича</option>
                        <option>1 кирпич</option>
                        <option>1.5 кирпича</option>
                        <option>2 кирпича</option>
                    </select>
                </div>
            @elseif(str_contains(strtolower($category->name), 'обои'))
                <!-- Калькулятор обоев -->
                <!-- Компактный калькулятор обоев -->
                <div class="space-y-3">
                    <!-- Размеры комнаты -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs text-gray-700 font-medium">Ширина комнаты</label>
                            <div class="relative mt-1">
                                <input type="number" value="4" class="w-full border border-gray-300 rounded-lg p-2 pr-6 text-xs focus:border-orange-500 focus:ring-orange-500">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-gray-700 font-medium">Длина комнаты</label>
                            <div class="relative mt-1">
                                <input type="number" value="4" class="w-full border border-gray-300 rounded-lg p-2 pr-6 text-xs focus:border-orange-500 focus:ring-orange-500">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-gray-700 font-medium">Высота комнаты</label>
                        <div class="relative mt-1">
                            <input type="number" value="2.7" class="w-full border border-gray-300 rounded-lg p-2 pr-6 text-xs focus:border-orange-500 focus:ring-orange-500">
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м</span>
                        </div>
                    </div>

                    <!-- Окна и двери в аккордеоне -->
                    <div x-data="{ open: false }" class="border border-gray-200 rounded-lg">
                        <button @click="open = !open" class="w-full flex items-center justify-between p-2 text-xs font-medium text-gray-700">
                            <span>Окна и двери</span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" class="p-2 border-t border-gray-200 space-y-2">
                            <div class="flex gap-1 text-xs">
                                <input type="number" placeholder="Ш" class="w-1/2 border border-gray-300 rounded p-1 text-xs">
                                <span class="self-center text-gray-500">×</span>
                                <input type="number" placeholder="В" class="w-1/2 border border-gray-300 rounded p-1 text-xs">
                                <span class="self-center text-gray-500 text-xs">м</span>
                            </div>
                            <button class="w-full flex items-center justify-center gap-1 text-orange-500 hover:text-orange-600 text-xs border border-dashed border-orange-300 rounded p-1 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Добавить
                            </button>
                        </div>
                    </div>

                    <!-- Размеры обоев -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs text-gray-700 font-medium">Ширина рулона</label>
                            <div class="relative mt-1">
                                <input type="number" value="53" class="w-full border border-gray-300 rounded-lg p-2 pr-8 text-xs focus:border-orange-500 focus:ring-orange-500">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">см</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-gray-700 font-medium">Длина рулона</label>
                            <div class="relative mt-1">
                                <input type="number" value="10" class="w-full border border-gray-300 rounded-lg p-2 pr-6 text-xs focus:border-orange-500 focus:ring-orange-500">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs text-gray-700 font-medium">Раппорт</label>
                            <div class="relative mt-1">
                                <input type="number" value="0" class="w-full border border-gray-300 rounded-lg p-2 pr-8 text-xs focus:border-orange-500 focus:ring-orange-500">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">см</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-gray-700 font-medium">Смещение</label>
                            <select class="w-full mt-1 border border-gray-300 rounded-lg p-2 text-xs focus:border-orange-500 focus:ring-orange-500">
                                <option>Нет</option>
                                <option>Есть</option>
                            </select>
                        </div>
                    </div>
                </div>
            @elseif(str_contains(strtolower($category->name), 'Эмал'))
                <!-- Калькулятор краски -->
                <div>
                    <label class="text-sm text-gray-700 font-medium">Площадь поверхности</label>
                    <div class="relative mt-1">
                        <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м²</span>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">Количество слоёв</label>
                    <select class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <option>1 слой</option>
                        <option>2 слоя</option>
                        <option>3 слоя</option>
                    </select>
                </div>
            @else
                <!-- Общий калькулятор -->
                <div>
                    <label class="text-sm text-gray-700 font-medium">Площадь/Объём</label>
                    <div class="relative mt-1">
                        <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м²</span>
                    </div>
                </div>
            @endif

            @if(!str_contains(strtolower($category->name), 'обои'))
                <div>
                    <label class="text-sm text-gray-700 font-medium">Расход на ед.</label>
                    <div class="relative mt-1">
                        <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-10 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м²</span>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">Цена за единицу</label>
                    <div class="relative mt-1">
                        <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">₽</span>
                    </div>
                </div>
            @endif

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
    let userProjects = [];
    let selectedMaterial = null;
    const currentCategoryId = {{ $category->id }};
    const urlParams = new URLSearchParams(window.location.search);
    const projectIdFromUrl = urlParams.get('project_id');

    // Fetch materials from API for current category
    async function loadMaterials() {
        try {
            showLoading();

            // Use the correct API endpoint with category filter
            const baseUrl = window.location.origin;
            const response = await fetch(`${baseUrl}/api/materials?category_id=${currentCategoryId}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            allMaterials = await response.json();
            console.log('Loaded materials for category:', allMaterials);

            filteredMaterials = [...allMaterials];

            // Populate filter dropdowns
            populateFilters();

            // Display materials
            displayMaterials();

            // Load user projects
            await loadUserProjects();

            // If project_id is in URL, preselect it
            if (projectIdFromUrl) {
                document.getElementById('project-select').value = projectIdFromUrl;
            }

            hideLoading();
        } catch (error) {
            console.error('Error loading materials:', error);
            showError('Ошибка загрузки материалов: ' + error.message);
        }
    }

    // Load user projects for selector
    async function loadUserProjects() {
        const token = localStorage.getItem('auth_token');
        if (!token) return;

        try {
            const response = await fetch('/api/projects', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                userProjects = await response.json();
                populateProjectSelector();
            }
        } catch (error) {
            console.error('Error loading projects:', error);
        }
    }

    function populateProjectSelector() {
        const projectSelect = document.getElementById('project-select');
        if (!projectSelect) return;

        // Clear existing options except the first one
        projectSelect.innerHTML = '<option value="">Выберите проект...</option>';

        userProjects.forEach(project => {
            const option = document.createElement('option');
            option.value = project.id;
            option.textContent = project.name;
            projectSelect.appendChild(option);
        });
    }

    function populateFilters() {
        const brandSelect = document.getElementById('filter-brand');
        const colorSelect = document.getElementById('filter-color');
        const supplierSelect = document.getElementById('filter-supplier');

        if (!brandSelect || !colorSelect || !supplierSelect) return;

        // Get unique brands, colors, and suppliers from filtered materials
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

        // Get latest price from prices array
        const price = material.prices && material.prices.length > 0
            ? material.prices[material.prices.length - 1].price
            : null;

        // Get supplier name
        const supplierName = material.supplier ? material.supplier.name : 'Не указан';

        // Очищаем URL картинки от HTML-сущностей
        let imageUrl = material.image_url;
        if (imageUrl) {
            imageUrl = imageUrl.replace(/&amp;/g, '&');
        }

        card.innerHTML = `
        <!-- Фото -->
        <div class="h-40 bg-white flex items-center justify-center p-4">
            ${imageUrl
            ? `<img src="${imageUrl}"
                       alt="${material.name}"
                       class="max-h-full object-contain"
                       onerror="handleImageError(this)">`
            : `<div class="text-gray-400 text-center">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm">No image</span>
                </div>`
        }
        </div>

        <!-- Контент -->
        <div class=" p-4 text-sm text-gray-700 flex flex-col gap-1 flex-grow">
            <h3 class="font-semibold text-lg text-gray-900 h-fit overflow-hidden">
                ${material.name}
            </h3>
            <p class="text-gray-500">${material.article || ''}</p>

            ${dimensions ? `<div class="flex justify-between"><span>Размер:</span><span>${dimensions} мм</span></div>` : ''}
            ${material.weight_kg ? `<div class="flex justify-between"><span>Вес:</span><span>${material.weight_kg} кг</span></div>` : ''}
            ${material.color ? `<div class="flex justify-between"><span>Цвет:</span><span>${material.color}</span></div>` : ''}
            ${material.brand ? `<div class="flex justify-between"><span>Бренд:</span><span>${material.brand}</span></div>` : ''}
            <div class="flex justify-between"><span>Поставщик:</span><span>${supplierName}</span></div>

            <p class="text-xl font-bold text-gray-900 mt-auto">
                ${price ? `${formatPrice(price)} ₽ / ${material.unit}` : 'Цена не указана'}
            </p>

            <!-- Количество -->
            <div class="mt-3 mx-auto border border-gray-300 rounded-xl px-4 py-2 w-full flex items-center justify-between gap-3">
                <button onclick="changeQuantity(this, -1)" class="text-2xl leading-none text-gray-600 hover:text-black">–</button>
                <input type="number" min="1" value="1" class="quantity-input w-12 text-center outline-none bg-transparent text-lg font-medium border-0 focus:ring-0">
                <span class="text-sm text-gray-700">${material.unit}</span>
                <button onclick="changeQuantity(this, 1)" class="text-2xl leading-none text-gray-600 hover:text-black">+</button>
            </div>

            <div class="flex gap-2 mt-3">
                <button onclick="addToEstimate(${material.id})" class="bg-orange-500 text-white py-2 px-3 rounded-lg hover:bg-orange-600 transition flex-1 text-sm">
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

    // Функция изменения количества
    function changeQuantity(button, change) {
        const container = button.closest('div');
        const input = container.querySelector('.quantity-input');
        let currentValue = parseInt(input.value) || 1;
        let newValue = currentValue + change;

        if (newValue < 1) newValue = 1;
        input.value = newValue;
    }

    // Функция добавления в смету
    function addToEstimate(materialId) {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            alert('Для добавления материалов в смету необходимо авторизоваться');
            window.location.href = '/login';
            return;
        }

        // Find the material card and get quantity
        const materialCard = document.querySelector(`[onclick="addToEstimate(${materialId})"]`).closest('.bg-white');
        const quantityInput = materialCard.querySelector('.quantity-input');
        const quantity = parseInt(quantityInput.value) || 1;

        // Find material data
        const material = allMaterials.find(m => m.id === materialId);
        if (!material) return;

        selectedMaterial = {
            id: material.id,
            name: material.name,
            quantity: quantity,
            unit: material.unit,
            price: material.prices && material.prices.length > 0 ? material.prices[material.prices.length - 1].price : 0
        };

        // Show project selector
        showProjectSelector();
    }

    // Показать выбор проекта
    function showProjectSelector() {
        const selector = document.getElementById('project-selector');
        if (selector) {
            selector.classList.remove('hidden');
            selector.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Скрыть выбор проекта
    function hideProjectSelector() {
        const selector = document.getElementById('project-selector');
        if (selector) {
            selector.classList.add('hidden');
        }
        selectedMaterial = null;
    }

    // Добавить материал в выбранный проект
    // Добавить материал в выбранный проект
    async function addToSelectedProject() {
        const projectSelect = document.getElementById('project-select');
        const selectedProjectId = projectSelect.value;

        if (!selectedProjectId) {
            alert('Пожалуйста, выберите проект');
            return;
        }

        if (!selectedMaterial) {
            alert('Ошибка: материал не выбран');
            return;
        }

        const token = localStorage.getItem('auth_token');
        if (!token) {
            alert('Ошибка авторизации');
            window.location.href = '/login';
            return;
        }

        try {
            console.log('Sending request to add material:', {
                projectId: selectedProjectId,
                materialId: selectedMaterial.id,
                quantity: selectedMaterial.quantity
            });

            const response = await fetch(`/api/projects/${selectedProjectId}/materials`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    material_id: selectedMaterial.id,
                    quantity: selectedMaterial.quantity
                })
            });

            console.log('Response status:', response.status);

            if (response.ok) {
                const result = await response.json();
                console.log('Success response:', result);
                showSuccess('Материал добавлен в смету!');
                hideProjectSelector();

                // Redirect to project page if project_id was in URL
                if (projectIdFromUrl) {
                    setTimeout(() => {
                        window.location.href = `/project/${selectedProjectId}`;
                    }, 1500);
                }
            } else {
                let errorMessage = 'Ошибка при добавлении материала';

                try {
                    const errorData = await response.json();
                    console.error('Error response data:', errorData);
                    errorMessage = errorData.message || errorMessage;

                    if (errorData.error) {
                        errorMessage += ': ' + errorData.error;
                    }
                } catch (parseError) {
                    // Если не удалось распарсить JSON
                    const textError = await response.text();
                    console.error('Error response text:', textError);
                    errorMessage = textError || errorMessage;
                }

                throw new Error(errorMessage);
            }
        } catch (error) {
            console.error('Network error adding material:', error);
            showError('Ошибка при добавлении материала: ' + error.message);
        }
    }

    // Добавьте эту функцию для обработки ошибок загрузки изображений
    function handleImageError(img) {
        img.style.display = 'none';
        const parent = img.parentNode;
        parent.innerHTML = `
        <div class="text-gray-400 text-center">
            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="text-sm">No image</span>
        </div>
    `;
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
            const price = material.prices && material.prices.length > 0
                ? material.prices[material.prices.length - 1].price
                : 0;
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

    function showSuccess(message) {
        // Создаем уведомление об успехе
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
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
