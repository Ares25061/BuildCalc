<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои сметы — MaterialHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

@include('layouts.nav')

<div class="max-w-7xl mx-auto px-4 py-10">

    <!-- Заголовок -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Мои сметы</h1>
            <p class="text-gray-600 mt-2 text-sm">Все ваши расчёты в одном месте</p>
        </div>

        <a href="#"
           class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-medium shadow transition">
            + Создать смету
        </a>
    </div>

    <!-- ✅ Фильтры — обновлённый вид -->
    <div class="bg-white p-5 rounded-xl shadow border border-gray-200 mb-10 flex flex-wrap gap-4 items-center justify-between">

        <input type="text"
               placeholder="Поиск по названию..."
               class="w-full md:w-1/3 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none">

        <div class="flex gap-2">
            <button class="filter-btn bg-orange-50 text-orange-600 px-4 py-2 rounded-lg text-sm shadow-sm">
                Все
            </button>
            <button class="filter-btn bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm">
                В работе
            </button>
            <button class="filter-btn bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm">
                Готовые
            </button>
            <button class="filter-btn bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm">
                Черновики
            </button>
        </div>

        <select class="border rounded-lg px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100">
            <option>Сортировать: по дате</option>
            <option>Сортировать: по стоимости</option>
            <option>Сортировать: по названию</option>
        </select>
    </div>

    <!-- ✅ Разные карточки -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-7">

        <!-- Карточка 1 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow hover:shadow-lg transition flex flex-col">
            <h3 class="text-lg font-semibold text-gray-900">Ремонт кухни</h3>
            <p class="text-gray-500 text-sm mt-1">Материалы</p>

            <div class="mt-4 flex justify-between text-sm">
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700">В работе</span>
                <span class="font-semibold text-green-600">16 747 ₽</span>
            </div>

            <a href="/test"
               class="mt-6 text-center bg-orange-50 text-orange-600 px-5 py-3 rounded-xl border hover:bg-orange-100 transition">
                Открыть
            </a>
        </div>

        <!-- Карточка 2 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow hover:shadow-lg transition flex flex-col">
            <h3 class="text-lg font-semibold text-gray-900">Строительство гаража</h3>
            <p class="text-gray-500 text-sm mt-1">Материалы</p>

            <div class="mt-4 flex justify-between text-sm">
                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">Готово</span>
                <span class="font-semibold text-green-600">480 000 ₽</span>
            </div>

            <a href="/projects/2"
               class="mt-6 text-center bg-orange-50 text-orange-600 px-5 py-3 rounded-xl border hover:bg-orange-100 transition">
                Открыть
            </a>
        </div>

        <!-- Карточка 3 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow hover:shadow-lg transition flex flex-col">
            <h3 class="text-lg font-semibold text-gray-900">Укладка плитки в ванной</h3>
            <p class="text-gray-500 text-sm mt-1">Черновой расчёт</p>

            <div class="mt-4 flex justify-between text-sm">
                <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-700">Черновик</span>
                <span class="font-semibold text-gray-800">110 000 ₽</span>
            </div>

            <a href="/projects/3"
               class="mt-6 text-center bg-orange-50 text-orange-600 px-5 py-3 rounded-xl border hover:bg-orange-100 transition">
                Открыть
            </a>
        </div>

    </div>

</div>

</body>
</html>
