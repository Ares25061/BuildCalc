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
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr_260px] gap-8">
        <aside class="bg-white p-6 shadow-md rounded-2xl border border-gray-200 space-y-4 h-fit top-6 self-start">
            <h2 class="text-lg font-semibold text-gray-900 text-center">Фильтры</h2>
            <div>
                <label class="text-sm text-gray-700 font-medium">Название</label>
                <input type="text" id="filter-name"
                       class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
            </div>
            <div>
                <label class="text-sm text-gray-700 font-medium">Бренд</label>
                <select id="filter-brand"
                        class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                    <option value="">Все</option>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-700 font-medium">Цвет</label>
                <select id="filter-color"
                        class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                    <option value="">Любой</option>
                </select>
            </div>
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
            <button id="apply-filters"
                    class="bg-orange-400 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                Применить
            </button>

        </aside>
        <section>
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
                <div id="loading-state" class="col-span-3 text-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto mb-4"></div>
                    <p class="text-gray-600">Загрузка материалов...</p>
                </div>
            </div>
        </section>
        <aside class="bg-white p-6 shadow-md rounded-2xl border border-gray-200 space-y-4 h-fit top-6 self-start" id="calculator-panel">
            <h3 class="text-lg font-semibold text-gray-900 gap-2 text-center">Калькулятор</h3>
            <div id="selected-material-panel" class="hidden">
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mb-4">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium text-gray-900">Выбранный материал</h4>
                        <button onclick="clearSelectedMaterial()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="selected-material-info" class="text-sm text-gray-700 space-y-1">
                    </div>
                </div>
            </div>
            <div id="general-calculator" class="space-y-4">
                <div>
                    <label class="text-sm text-gray-700 font-medium">Площадь/Объём</label>
                    <div class="relative mt-1">
                        <input type="number" id="general-area" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м²</span>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">Расход на ед.</label>
                    <div class="relative mt-1">
                        <input type="number" id="general-consumption" class="w-full border border-gray-300 rounded-lg p-2.5 pr-10 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м²</span>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-700 font-medium">Цена за единицу</label>
                    <div class="relative mt-1">
                        <input type="number" id="general-price" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">₽</span>
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700">Необходимое количество:</span>
                        <span id="general-quantity" class="font-medium">-</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-gray-700">Общая стоимость:</span>
                        <span id="general-total" class="font-medium">-</span>
                    </div>
                </div>

                <button onclick="calculateGeneral()" class="bg-gray-800 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                    Рассчитать
                </button>
            </div>
            <div id="brick-calculator" class="space-y-4 hidden">
                <div>
                    <label class="text-sm text-gray-700 font-medium">Размер кирпича</label>
                    <select id="brick-size" class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <option value="250x120x65">250×120×65 мм</option>
                        <option value="250x120x88">250×120×88 мм</option>
                        <option value="230x110x65">230×110×65 мм</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-700 font-medium">Длина стен</label>
                    <div class="relative mt-1">
                        <input type="number" id="brick-length" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м</span>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-700 font-medium">Высота стен</label>
                    <div class="relative mt-1">
                        <input type="number" id="brick-height" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м</span>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-700 font-medium">Толщина стен</label>
                    <select id="brick-thickness" class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <option value="0.5">0.5 кирпича</option>
                        <option value="1">1 кирпич</option>
                        <option value="1.5">1.5 кирпича</option>
                        <option value="2">2 кирпича</option>
                    </select>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700">Необходимое количество:</span>
                        <span id="brick-quantity" class="font-medium">-</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-gray-700">Общая стоимость:</span>
                        <span id="brick-total" class="font-medium">-</span>
                    </div>
                </div>
                <button onclick="calculateBrick()" class="bg-gray-800 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                    Рассчитать
                </button>
            </div>
            <div id="wallpaper-calculator" class="space-y-3 hidden">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs text-gray-700 font-medium">Ширина комнаты</label>
                        <div class="relative mt-1">
                            <input type="number" id="wallpaper-width" value="4" class="w-full border border-gray-300 rounded-lg p-2 pr-6 text-xs focus:border-orange-500 focus:ring-orange-500">
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-700 font-medium">Длина комнаты</label>
                        <div class="relative mt-1">
                            <input type="number" id="wallpaper-length" value="4" class="w-full border border-gray-300 rounded-lg p-2 pr-6 text-xs focus:border-orange-500 focus:ring-orange-500">
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м</span>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-700 font-medium">Высота комнаты</label>
                    <div class="relative mt-1">
                        <input type="number" id="wallpaper-height" value="2.7" class="w-full border border-gray-300 rounded-lg p-2 pr-6 text-xs focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м</span>
                    </div>
                </div>
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
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs text-gray-700 font-medium">Ширина рулона</label>
                        <div class="relative mt-1">
                            <input type="number" id="wallpaper-roll-width" value="53" class="w-full border border-gray-300 rounded-lg p-2 pr-8 text-xs focus:border-orange-500 focus:ring-orange-500">
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">см</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-700 font-medium">Длина рулона</label>
                        <div class="relative mt-1">
                            <input type="number" id="wallpaper-roll-length" value="10" class="w-full border border-gray-300 rounded-lg p-2 pr-6 text-xs focus:border-orange-500 focus:ring-orange-500">
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs text-gray-700 font-medium">Раппорт</label>
                        <div class="relative mt-1">
                            <input type="number" id="wallpaper-rapport" value="0" class="w-full border border-gray-300 rounded-lg p-2 pr-8 text-xs focus:border-orange-500 focus:ring-orange-500">
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs">см</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-700 font-medium">Смещение</label>
                        <select id="wallpaper-offset" class="w-full mt-1 border border-gray-300 rounded-lg p-2 text-xs focus:border-orange-500 focus:ring-orange-500">
                            <option value="no">Нет</option>
                            <option value="yes">Есть</option>
                        </select>
                    </div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700">Необходимое количество рулонов:</span>
                        <span id="wallpaper-rolls" class="font-medium">-</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-gray-700">Общая стоимость:</span>
                        <span id="wallpaper-total" class="font-medium">-</span>
                    </div>
                </div>
                <button onclick="calculateWallpaper()" class="bg-gray-800 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                    Рассчитать
                </button>
            </div>
            <div id="paint-calculator" class="space-y-4 hidden">
                <div>
                    <label class="text-sm text-gray-700 font-medium">Площадь поверхности</label>
                    <div class="relative mt-1">
                        <input type="number" id="paint-area" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м²</span>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-700 font-medium">Количество слоёв</label>
                    <select id="paint-layers" class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <option value="1">1 слой</option>
                        <option value="2">2 слоя</option>
                        <option value="3">3 слоя</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-700 font-medium">Расход на м²</label>
                    <div class="relative mt-1">
                        <input type="number" id="paint-consumption" class="w-full border border-gray-300 rounded-lg p-2.5 pr-10 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-xs">л/м²</span>
                    </div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700">Необходимое количество:</span>
                        <span id="paint-quantity" class="font-medium">-</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-gray-700">Общая стоимость:</span>
                        <span id="paint-total" class="font-medium">-</span>
                    </div>
                </div>
                <button onclick="calculatePaint()" class="bg-gray-800 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                    Рассчитать
                </button>
            </div>
            <div id="tile-calculator" class="space-y-4 hidden">
                <div>
                    <label class="text-sm text-gray-700 font-medium">Площадь поверхности</label>
                    <div class="relative mt-1">
                        <input type="number" id="tile-area" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м²</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-sm text-gray-700 font-medium">Размер плитки</label>
                        <div class="relative mt-1">
                            <input type="number" id="tile-width" placeholder="Ш" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-700 font-medium invisible">×</label>
                        <div class="relative mt-1">
                            <input type="number" id="tile-height" placeholder="В" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-700 font-medium">Ширина шва</label>
                    <div class="relative mt-1">
                        <input type="number" id="tile-gap" value="2" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-xs">мм</span>
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700">Необходимое количество:</span>
                        <span id="tile-quantity" class="font-medium">-</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-gray-700">Общая стоимость:</span>
                        <span id="tile-total" class="font-medium">-</span>
                    </div>
                </div>
                <button onclick="calculateTile()" class="bg-gray-800 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                    Рассчитать
                </button>
            </div>
        </aside>
    </div>
</div>

<script>
    let allMaterials = [];
    let filteredMaterials = [];
    let userProjects = [];
    let selectedMaterial = null;
    let selectedWorkPositionId = null;
    const currentCategoryId = {{ $category->id }};
    const currentCategoryName = "{{ $category->name }}";
    const urlParams = new URLSearchParams(window.location.search);
    const projectIdFromUrl = urlParams.get('project_id');
    const categoriesWithoutCalculator = [
        'stroitelnoe-oborudovanie',
        'stroitelnye-rashodnye-materialy'
    ];

    function getCalculatorType(categoryName) {
        const name = categoryName.toLowerCase();

        if (name.includes('кирпич') || name.includes('блок')) {
            return 'brick';
        } else if (name.includes('обои')) {
            return 'wallpaper';
        } else if (name.includes('эмал') || name.includes('краск')) {
            return 'paint';
        } else if (name.includes('плитка')) {
            return 'tile';
        } else {
            return 'general';
        }
    }

    function initializeCalculator() {
        const calculatorType = getCalculatorType(currentCategoryName);
        document.getElementById('general-calculator').classList.add('hidden');
        document.getElementById('brick-calculator').classList.add('hidden');
        document.getElementById('wallpaper-calculator').classList.add('hidden');
        document.getElementById('paint-calculator').classList.add('hidden');
        document.getElementById('tile-calculator').classList.add('hidden');

        if (calculatorType === 'brick') {
            document.getElementById('brick-calculator').classList.remove('hidden');
        } else if (calculatorType === 'wallpaper') {
            document.getElementById('wallpaper-calculator').classList.remove('hidden');
        } else if (calculatorType === 'paint') {
            document.getElementById('paint-calculator').classList.remove('hidden');
        } else if (calculatorType === 'tile') {
            document.getElementById('tile-calculator').classList.remove('hidden');
        } else {
            document.getElementById('general-calculator').classList.remove('hidden');
        }
    }

    function needsCalculatorButton(categoryName) {
        const name = categoryName.toLowerCase();
        return !categoriesWithoutCalculator.some(cat => name.includes(cat));
    }

    function addToCalculator(materialId) {
        const material = allMaterials.find(m => m.id === materialId);
        if (!material) return;

        selectedMaterial = material;
        updateSelectedMaterialPanel();
        fillCalculatorWithMaterialData(material);
    }

    function updateSelectedMaterialPanel() {
        const panel = document.getElementById('selected-material-panel');
        const info = document.getElementById('selected-material-info');

        if (selectedMaterial) {
            const price = selectedMaterial.prices && selectedMaterial.prices.length > 0
                ? selectedMaterial.prices[selectedMaterial.prices.length - 1].price
                : 0;

            info.innerHTML = `
                <div class="font-medium">${selectedMaterial.name}</div>
                <div>${selectedMaterial.brand || ''}</div>
                <div>${selectedMaterial.color || ''}</div>
                <div class="font-semibold">${formatPrice(price)} ₽ / ${selectedMaterial.unit}</div>
            `;

            panel.classList.remove('hidden');
        } else {
            panel.classList.add('hidden');
        }
    }

    function clearSelectedMaterial() {
        selectedMaterial = null;
        updateSelectedMaterialPanel();
    }

    function fillCalculatorWithMaterialData(material) {
        const calculatorType = getCalculatorType(currentCategoryName);
        const price = material.prices && material.prices.length > 0
            ? material.prices[material.prices.length - 1].price
            : 0;

        if (calculatorType === 'general') {
            document.getElementById('general-price').value = price;
        } else if (calculatorType === 'brick') {
            if (material.length_mm && material.width_mm && material.height_mm) {
                const sizeOption = `${material.length_mm}x${material.width_mm}x${material.height_mm}`;
                const sizeSelect = document.getElementById('brick-size');
                for (let i = 0; i < sizeSelect.options.length; i++) {
                    if (sizeSelect.options[i].value === sizeOption) {
                        sizeSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        } else if (calculatorType === 'paint') {
            document.getElementById('paint-price').value = price;
        } else if (calculatorType === 'tile') {
            document.getElementById('tile-price').value = price;
            if (material.length_mm && material.width_mm) {
                document.getElementById('tile-width').value = material.length_mm;
                document.getElementById('tile-height').value = material.width_mm;
            }
        }
    }


    function calculateGeneral() {
        const area = parseFloat(document.getElementById('general-area').value) || 0;
        const consumption = parseFloat(document.getElementById('general-consumption').value) || 0;
        const price = parseFloat(document.getElementById('general-price').value) || 0;

        if (!selectedMaterial) {
            alert('Пожалуйста, выберите материал для расчета');
            return;
        }

        const quantity = area * consumption;
        const total = quantity * price;

        document.getElementById('general-quantity').textContent = `${quantity.toFixed(2)} ${selectedMaterial.unit}`;
        document.getElementById('general-total').textContent = `${formatPrice(total)} ₽`;
    }

    function calculateBrick() {
        const length = parseFloat(document.getElementById('brick-length').value) || 0;
        const height = parseFloat(document.getElementById('brick-height').value) || 0;
        const thickness = parseFloat(document.getElementById('brick-thickness').value) || 1;

        if (!selectedMaterial) {
            alert('Пожалуйста, выберите материал для расчета');
            return;
        }
        const brickVolume = 0.25 * 0.12 * 0.065;
        const wallVolume = length * height * (thickness * 0.12);
        const quantity = Math.ceil(wallVolume / brickVolume * 1.05);

        const price = selectedMaterial.prices && selectedMaterial.prices.length > 0
            ? selectedMaterial.prices[selectedMaterial.prices.length - 1].price
            : 0;
        const total = quantity * price;

        document.getElementById('brick-quantity').textContent = `${quantity} шт`;
        document.getElementById('brick-total').textContent = `${formatPrice(total)} ₽`;
    }

    function calculateWallpaper() {
        const width = parseFloat(document.getElementById('wallpaper-width').value) || 0;
        const length = parseFloat(document.getElementById('wallpaper-length').value) || 0;
        const height = parseFloat(document.getElementById('wallpaper-height').value) || 0;
        const rollWidth = parseFloat(document.getElementById('wallpaper-roll-width').value) || 53;
        const rollLength = parseFloat(document.getElementById('wallpaper-roll-length').value) || 10;

        if (!selectedMaterial) {
            alert('Пожалуйста, выберите материал для расчета');
            return;
        }

        const perimeter = (width + length) * 2;
        const stripsPerRoll = Math.floor(rollLength / height);
        const stripsNeeded = Math.ceil(perimeter / (rollWidth / 100));
        const rolls = Math.ceil(stripsNeeded / stripsPerRoll * 1.1);

        const price = selectedMaterial.prices && selectedMaterial.prices.length > 0
            ? selectedMaterial.prices[selectedMaterial.prices.length - 1].price
            : 0;
        const total = rolls * price;

        document.getElementById('wallpaper-rolls').textContent = `${rolls} шт`;
        document.getElementById('wallpaper-total').textContent = `${formatPrice(total)} ₽`;
    }

    function calculatePaint() {
        const area = parseFloat(document.getElementById('paint-area').value) || 0;
        const layers = parseInt(document.getElementById('paint-layers').value) || 1;
        const consumption = parseFloat(document.getElementById('paint-consumption').value) || 0;

        if (!selectedMaterial) {
            alert('Пожалуйста, выберите материал для расчета');
            return;
        }

        const quantity = area * consumption * layers;

        const price = selectedMaterial.prices && selectedMaterial.prices.length > 0
            ? selectedMaterial.prices[selectedMaterial.prices.length - 1].price
            : 0;
        const total = quantity * price;

        document.getElementById('paint-quantity').textContent = `${quantity.toFixed(2)} л`;
        document.getElementById('paint-total').textContent = `${formatPrice(total)} ₽`;
    }

    function calculateTile() {
        const area = parseFloat(document.getElementById('tile-area').value) || 0;
        const tileWidth = parseFloat(document.getElementById('tile-width').value) || 0;
        const tileHeight = parseFloat(document.getElementById('tile-height').value) || 0;

        if (!selectedMaterial) {
            alert('Пожалуйста, выберите материал для расчета');
            return;
        }

        const tileArea = (tileWidth / 1000) * (tileHeight / 1000);
        const quantity = Math.ceil(area / tileArea * 1.1);

        const price = selectedMaterial.prices && selectedMaterial.prices.length > 0
            ? selectedMaterial.prices[selectedMaterial.prices.length - 1].price
            : 0;
        const total = quantity * price;

        document.getElementById('tile-quantity').textContent = `${quantity} шт`;
        document.getElementById('tile-total').textContent = `${formatPrice(total)} ₽`;
    }

    async function loadMaterials() {
        try {
            showLoading();
            const baseUrl = window.location.origin;
            const response = await fetch(`${baseUrl}/api/materials?category_id=${currentCategoryId}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            allMaterials = await response.json();
            console.log('Loaded materials for category:', allMaterials);
            filteredMaterials = [...allMaterials];
            populateFilters();
            displayMaterials();
            await loadUserProjects();

            if (projectIdFromUrl) {
                document.getElementById('project-select').value = projectIdFromUrl;
            }

            initializeCalculator();

            hideLoading();
        } catch (error) {
            console.error('Error loading materials:', error);
            showError('Ошибка загрузки материалов: ' + error.message);
        }
    }

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
        const brands = [...new Set(allMaterials.map(m => m.brand).filter(Boolean))];
        const colors = [...new Set(allMaterials.map(m => m.color).filter(Boolean))];
        const suppliers = [...new Set(allMaterials.map(m => m.supplier?.name).filter(Boolean))];
        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            brandSelect.appendChild(option);
        });
        colors.forEach(color => {
            const option = document.createElement('option');
            option.value = color;
            option.textContent = color;
            colorSelect.appendChild(option);
        });
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
        const dimensions = material.length_mm && material.width_mm && material.height_mm
            ? `${material.length_mm}×${material.width_mm}×${material.height_mm}`
            : null;
        const price = material.prices && material.prices.length > 0
            ? material.prices[material.prices.length - 1].price
            : null;
        const supplierName = material.supplier ? material.supplier.name : 'Не указан';
        let imageUrl = material.image_url;
        if (imageUrl) {
            imageUrl = imageUrl.replace(/&amp;/g, '&');
        }

        const showCalculateButton = needsCalculatorButton(currentCategoryName);

        card.innerHTML = `
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
                ${showCalculateButton ? `
                <button onclick="addToCalculator(${material.id})" class="bg-gray-800 text-white py-2 px-3 rounded-lg hover:bg-gray-600 transition flex-1 text-sm border border-gray-300">
                    Рассчитать
                </button>
                ` : ''}
            </div>
        </div>
    `;

        return card;
    }

    function changeQuantity(button, change) {
        const container = button.closest('div');
        const input = container.querySelector('.quantity-input');
        let currentValue = parseInt(input.value) || 1;
        let newValue = currentValue + change;

        if (newValue < 1) newValue = 1;
        input.value = newValue;
    }
    function addToEstimate(materialId) {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            alert('Для добавления материалов в смету необходимо авторизоваться');
            window.location.href = '/login';
            return;
        }
        const materialCard = document.querySelector(`[onclick="addToEstimate(${materialId})"]`).closest('.bg-white');
        const quantityInput = materialCard.querySelector('.quantity-input');
        const quantity = parseInt(quantityInput.value) || 1;
        const material = allMaterials.find(m => m.id === materialId);
        if (!material) return;

        selectedMaterial = {
            id: material.id,
            name: material.name,
            quantity: quantity,
            unit: material.unit,
            price: material.prices && material.prices.length > 0 ? material.prices[material.prices.length - 1].price : 0
        };
        selectedWorkPositionId = null;
        showProjectSelector();
    }
    async function loadWorkPositionsForProject() {
        const projectSelect = document.getElementById('project-select');
        const selectedProjectId = projectSelect.value;

        if (!selectedProjectId) {
            alert('Пожалуйста, выберите проект');
            return;
        }

        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch(`/api/projects/${selectedProjectId}/items`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const workPositions = await response.json();
                showWorkPositionSelector(workPositions, selectedProjectId);
            } else {
                throw new Error('Не удалось загрузить позиции работ');
            }
        } catch (error) {
            console.error('Error loading work positions:', error);
            showError('Ошибка при загрузке позиций работ: ' + error.message);
        }
    }
    function showWorkPositionSelector(workPositions, projectId) {
        const selector = document.getElementById('project-selector');
        if (!selector) return;

        let positionsHTML = '';

        if (workPositions.length > 0) {
            positionsHTML = `
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Выберите позицию работ:</label>
                <select id="work-position-select" class="w-full border border-gray-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500">
                    <option value="">Автоматически (создать новую позицию "Материалы")</option>
                    ${workPositions.map(position => `
                        <option value="${position.id}">${position.work_type_name}${position.notes ? ` - ${position.notes}` : ''} (${position.materials_count} материалов)</option>
                    `).join('')}
                </select>
            </div>
        `;
        } else {
            positionsHTML = `
            <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-sm text-blue-700">В проекте пока нет позиций работ. Материал будет добавлен в новую позицию "Материалы".</p>
            </div>
        `;
        }

        selector.innerHTML = `
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Добавить материал в смету</h3>
                <p class="text-sm text-gray-600">Выберите позицию для добавления материала</p>
            </div>
            <button onclick="hideProjectSelector()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700">Проект: <span id="selected-project-name">${document.getElementById('project-select').options[document.getElementById('project-select').selectedIndex].text}</span></p>
            </div>
            ${positionsHTML}
            <div class="mt-3 flex gap-3">
                <button onclick="addToSelectedProjectAndPosition(${projectId})" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition flex-1">
                    Добавить в смету
                </button>
                <button onclick="showProjectSelector()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    Назад
                </button>
            </div>
        </div>
    `;

        selector.classList.remove('hidden');
    }
    function showProjectSelector() {
        const selector = document.getElementById('project-selector');
        if (!selector) return;

        selector.innerHTML = `
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
                <button onclick="loadWorkPositionsForProject()" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition flex-1">
                    Продолжить
                </button>
                <a href="/project/create" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-center flex items-center justify-center">
                    Новый проект
                </a>
            </div>
        </div>
    `;

        populateProjectSelector();
        selector.classList.remove('hidden');
        selector.scrollIntoView({ behavior: 'smooth' });
    }
    function hideProjectSelector() {
        const selector = document.getElementById('project-selector');
        if (selector) {
            selector.classList.add('hidden');
        }
        selectedMaterial = null;
        selectedWorkPositionId = null;
    }
    async function addToSelectedProjectAndPosition(projectId) {
        if (!selectedMaterial) {
            alert('Ошибка: материал не выбран');
            return;
        }

        const token = localStorage.getItem('auth_token');
        if (!token) {
            alert('Ошибка авторизации');
            return;
        }
        const workPositionSelect = document.getElementById('work-position-select');
        const selectedWorkPositionId = workPositionSelect ? workPositionSelect.value : null;

        try {
            console.log('Sending request to add material:', {
                projectId: projectId,
                materialId: selectedMaterial.id,
                quantity: selectedMaterial.quantity,
                project_item_id: selectedWorkPositionId || null
            });

            const requestBody = {
                material_id: selectedMaterial.id,
                quantity: selectedMaterial.quantity
            };

            if (selectedWorkPositionId) {
                requestBody.project_item_id = selectedWorkPositionId;
            }

            const response = await fetch(`/api/projects/${projectId}/materials`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });

            console.log('Response status:', response.status);

            if (response.ok) {
                const result = await response.json();
                console.log('Success response:', result);

                let successMessage = 'Материал добавлен в смету!';
                if (selectedWorkPositionId) {
                    const positionName = document.getElementById('work-position-select').options[document.getElementById('work-position-select').selectedIndex].text;
                    successMessage = `Материал добавлен в позицию "${positionName.split(' (')[0]}"!`;
                }

                showSuccess(successMessage);
                hideProjectSelector();

                if (projectIdFromUrl) {
                    setTimeout(() => {
                        window.location.href = `/project/${projectId}`;
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
            if (nameFilter && !material.name.toLowerCase().includes(nameFilter)) {
                return false;
            }

            if (brandFilter && material.brand !== brandFilter) {
                return false;
            }

            if (colorFilter && material.color !== colorFilter) {
                return false;
            }

            if (supplierFilter && material.supplier?.name !== supplierFilter) {
                return false;
            }

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
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const materialsContainer = document.getElementById('materials-container');
        if (materialsContainer) {
            loadMaterials();
            const applyButton = document.getElementById('apply-filters');
            if (applyButton) {
                applyButton.addEventListener('click', applyFilters);
            }
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
