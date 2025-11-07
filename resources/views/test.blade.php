<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Смета "Ремонт кухни" — MaterialHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

@include('layouts.nav')

<div class="max-w-7xl mx-auto px-4 py-10 space-y-10">

    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Ремонт кухни</h1>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium whitespace-nowrap">
                    В работе
                </span>
                    <button class="flex items-center justify-center gap-2 bg-gray-50 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200 font-medium text-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button class="flex items-center justify-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition-colors border border-red-200 font-medium text-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 mb-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Создано</p>
                            <p class="text-sm font-medium">01.11.2025</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Статус</p>
                            <p class="text-sm font-medium">В работе</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Позиций</p>
                            <p class="text-sm font-medium">6 материалов</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Бюджет</p>
                            <p class="text-sm font-medium text-green-600">20 000 ₽</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 lg:justify-end">
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Бюджет проекта</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-2xl font-bold text-blue-600">20 000 ₽</p>
                <p class="text-sm text-gray-600 mt-1">Общий бюджет</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-2xl font-bold text-green-600">16 747 ₽</p>
                <p class="text-sm text-gray-600 mt-1">Материалы</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                <p class="text-2xl font-bold text-green-600">+3 253 ₽</p>
                <p class="text-sm text-gray-600 mt-1">Экономия</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-semibold text-gray-900">Материалы для кухни</h2>
                <div class="flex gap-3">
                    <button class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                        Фильтры
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Группировать
                    </button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 bg-gray-50 text-gray-600 text-xs font-medium px-6 py-3">
            <div class="col-span-5">Наименование</div>
            <div class="col-span-1 text-center">Ед.</div>
            <div class="col-span-1 text-center">Кол-во</div>
            <div class="col-span-2 text-right">Цена за ед.</div>
            <div class="col-span-2 text-right">Сумма</div>
            <div class="col-span-1 text-center"></div>
        </div>
        <div class="grid grid-cols-12 px-6 py-3 border-t text-sm items-center hover:bg-gray-50 group">
            <div class="col-span-5">
                <div class="font-medium text-gray-900">Плитка настенная</div>
                <div class="text-xs text-gray-500 mt-1">Керамическая • 20x30 см</div>
            </div>
            <div class="col-span-1 text-center text-gray-600">шт.</div>
            <div class="col-span-1 text-center">
                <input type="number" value="20" class="w-16 text-center border rounded-lg p-1 focus:ring-orange-500 focus:border-orange-500 text-sm">
            </div>
            <div class="col-span-2 text-right text-gray-700">200 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">4000 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-12 px-6 py-3 border-t text-sm items-center hover:bg-gray-50 group">
            <div class="col-span-5">
                <div class="font-medium text-gray-900">Плитка напольная</div>
                <div class="text-xs text-gray-500 mt-1">Керамогранит • 45x45 см</div>
            </div>
            <div class="col-span-1 text-center text-gray-600">шт.</div>
            <div class="col-span-1 text-center">
                <input type="number" value="15" class="w-16 text-center border rounded-lg p-1 focus:ring-orange-500 focus:border-orange-500 text-sm">
            </div>
            <div class="col-span-2 text-right text-gray-700">350 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">5 250 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-12 px-6 py-3 border-t text-sm items-center hover:bg-gray-50 group">
            <div class="col-span-5">
                <div class="font-medium text-gray-900">Обои виниловые</div>
                <div class="text-xs text-gray-500 mt-1">Влагостойкие</div>
            </div>
            <div class="col-span-1 text-center text-gray-600">рул.</div>
            <div class="col-span-1 text-center">
                <input type="number" value="3" class="w-16 text-center border rounded-lg p-1 focus:ring-orange-500 focus:border-orange-500 text-sm">
            </div>
            <div class="col-span-2 text-right text-gray-700">999 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">2997 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-12 px-6 py-3 border-t text-sm items-center hover:bg-gray-50 group">
            <div class="col-span-5">
                <div class="font-medium text-gray-900">Шпатлевка финишная Vetonit LR+</div>
                <div class="text-xs text-gray-500 mt-1">20 кг</div>
            </div>
            <div class="col-span-1 text-center text-gray-600">шт.</div>
            <div class="col-span-1 text-center">
                <input type="number" value="1" class="w-16 text-center border rounded-lg p-1 focus:ring-orange-500 focus:border-orange-500 text-sm">
            </div>
            <div class="col-span-2 text-right text-gray-700">1200 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">1200 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-12 px-6 py-3 border-t text-sm items-center hover:bg-gray-50 group">
            <div class="col-span-5">
                <div class="font-medium text-gray-900">Грунтовка Ceresit CT 17 PRO</div>
                <div class="text-xs text-gray-500 mt-1">10 л</div>
            </div>
            <div class="col-span-1 text-center text-gray-600">шт.</div>
            <div class="col-span-1 text-center">
                <input type="number" value="1" class="w-16 text-center border rounded-lg p-1 focus:ring-orange-500 focus:border-orange-500 text-sm">
            </div>
            <div class="col-span-2 text-right text-gray-700">1 300 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">1 300 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-12 px-6 py-3 border-t text-sm items-center hover:bg-gray-50 group">
            <div class="col-span-5">
                <div class="font-medium text-gray-900">Плиточный клей UNIS 2000</div>
                <div class="text-xs text-gray-500 mt-1">Цементный • 25 кг</div>
            </div>
            <div class="col-span-1 text-center text-gray-600">шт.</div>
            <div class="col-span-1 text-center">
                <input type="number" value="2" class="w-16 text-center border rounded-lg p-1 focus:ring-orange-500 focus:border-orange-500 text-sm">
            </div>
            <div class="col-span-2 text-right text-gray-700">700 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">1 400 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-12 px-6 py-3 border-t text-sm items-center hover:bg-gray-50 group">
            <div class="col-span-5">
                <div class="font-medium text-gray-900">Затирка для плитки</div>
                <div class="text-xs text-gray-500 mt-1">Цвет белый • 2 кг</div>
            </div>
            <div class="col-span-1 text-center text-gray-600">шт.</div>
            <div class="col-span-1 text-center">
                <input type="number" value="1" class="w-16 text-center border rounded-lg p-1 focus:ring-orange-500 focus:border-orange-500 text-sm">
            </div>
            <div class="col-span-2 text-right text-gray-700">600 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">600 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="flex justify-between items-center p-4 border-t">
            <div class="text-sm text-gray-600">
                Всего позиций: <span class="font-medium">6</span>
            </div>
            <a href="/categories"
               class="flex items-center justify-center gap-2 bg-orange-50 text-orange-600 px-5 py-2 rounded-xl hover:bg-orange-100 transition-colors border border-orange-200 font-medium text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Добавить материал
            </a>
        </div>
    </div>
    <div class="flex justify-end">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow text-right w-full md:w-1/3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Финансовый итог</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Стоимость материалов:</span>
                    <span class="font-semibold text-gray-900">16 747 ₽</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Стоимость работ:</span>
                    <span class="font-semibold text-gray-900">0 ₽</span>
                </div>
                <hr class="my-3 border-gray-300">
                <div class="flex justify-between items-center text-xl font-bold text-green-600">
                    <span>Итого:</span>
                    <span>16 747 ₽</span>
                </div>
                <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center gap-2 text-sm text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">В рамках бюджета</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
