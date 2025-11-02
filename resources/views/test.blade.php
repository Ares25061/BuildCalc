<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Название сметы — MaterialHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

@include('layouts.nav')

<div class="max-w-7xl mx-auto px-4 py-10 space-y-10">

    <!-- ✅ Шапка проекта -->

        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Левая часть: название и информация -->
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-3">
                        <h1 class="text-3xl font-bold text-gray-900">Ремонт кухни</h1>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        В процессе
                    </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-6 text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm">Создано: 12.10.2025</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-sm">Итого: <span class="font-semibold text-green-600">230 000 ₽</span></span>
                        </div>
                    </div>
                </div>

                <!-- Правая часть: кнопки действий -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="flex items-center justify-center gap-2 bg-gray-50 text-gray-700 px-5 py-3 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Редактировать
                    </button>

                    <button class="flex items-center justify-center gap-2 bg-red-50 text-red-600 px-5 py-3 rounded-xl hover:bg-red-100 transition-colors border border-red-200 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Удалить
                    </button>
                </div>
            </div>
        </div>


    <!-- ✅ Таблица позиций -->
    <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">

        <!-- Header -->
        <div class="grid grid-cols-12 bg-gray-50 text-gray-600 text-xs font-medium px-4 py-3">
            <div class="col-span-5">Название</div>
            <div class="col-span-2 text-center">Кол-во</div>
            <div class="col-span-2 text-right">Цена за штуку</div>
            <div class="col-span-2 text-right">Сумма</div>
            <div class="col-span-1 text-center">Удалить</div>
        </div>

        <div class="grid grid-cols-12 px-4 py-4 border-t text-sm items-center hover:bg-gray-50">
            <div class="col-span-5 font-medium">Кирпич керамический</div>
            <div class="col-span-2 text-center">
                <input type="number" value="200"
                       class="w-20 text-center border rounded-lg p-1 focus:ring-orange-500">
            </div>
            <div class="col-span-2 text-right text-gray-800">24 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">4 800 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 px-4 py-4 border-t text-sm items-center hover:bg-gray-50">
            <div class="col-span-5 font-medium">Плитка настенная</div>
            <div class="col-span-2 text-center">
                <input type="number" value="150"
                       class="w-20 text-center border rounded-lg p-1 focus:ring-orange-500">
            </div>
            <div class="col-span-2 text-right text-gray-800">85 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">12 750 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 px-4 py-4 border-t text-sm items-center hover:bg-gray-50">
            <div class="col-span-5 font-medium">Смесь для стяжки</div>
            <div class="col-span-2 text-center">
                <input type="number" value="25"
                       class="w-20 text-center border rounded-lg p-1 focus:ring-orange-500">
            </div>
            <div class="col-span-2 text-right text-gray-800">320 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">8 000 ₽</div>
            <div class="col-span-1 text-center">
                <button class="text-red-500 hover:text-red-700 text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Кнопка "Добавить материал" - теперь справа с нормальными отступами -->
        <div class="flex justify-end p-4 border-t">
            <a href="/categories"
               class="flex items-center justify-center gap-2 bg-orange-50 text-orange-600 px-5 py-3 rounded-xl hover:bg-orange-100 transition-colors border border-orange-200 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Добавить материал
            </a>
        </div>
    </div>


    <!-- ✅ Панель итогов -->
    <div class="flex justify-end">

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow text-right w-full md:w-1/3">

            <div class="flex justify-between text-gray-600 text-sm mb-2">
                <span>Материалы:</span>
                <span class="font-semibold text-gray-900">180 000 ₽</span>
            </div>

            <div class="flex justify-between text-gray-600 text-sm mb-2">
                <span>Работы:</span>
                <span class="font-semibold text-gray-900">50 000 ₽</span>
            </div>

            <hr class="my-3">

            <div class="flex justify-between text-xl font-bold text-green-600">
                <span>Итого:</span>
                <span>230 000 ₽</span>
            </div>

        </div>
    </div>

</div>

</body>
</html>
