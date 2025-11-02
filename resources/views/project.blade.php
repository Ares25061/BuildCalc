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

    <!-- Панель фильтров -->
    <div class="bg-white p-4 rounded-xl shadow border border-gray-200 mb-8">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">

            <input type="text"
                   placeholder="Поиск по названию..."
                   class="w-full md:w-1/3 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none">

            <div class="flex gap-2">
                <button class="px-4 py-2 text-sm rounded-lg border bg-gray-50 hover:bg-gray-100">
                    Все
                </button>
                <button class="px-4 py-2 text-sm rounded-lg border bg-blue-50 text-blue-600 hover:bg-blue-100">
                    В работе
                </button>
                <button class="px-4 py-2 text-sm rounded-lg border bg-gray-50 hover:bg-gray-100">
                    Готовые
                </button>
                <button class="px-4 py-2 text-sm rounded-lg border bg-gray-50 hover:bg-gray-100">
                    Черновики
                </button>
            </div>

            <select class="border rounded-lg px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100">
                <option>Сортировать: по дате</option>
                <option>Сортировать: по стоимости</option>
                <option>Сортировать: по названию</option>
            </select>
        </div>
    </div>


    <!-- Пример карточек -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @for($i = 0; $i < 3; $i++)
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow flex flex-col justify-between">

                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Ремонт кухни</h3>
                    <p class="text-gray-500 text-sm mt-1">Материалы + работы</p>
                </div>

                <div class="mt-4 text-gray-700 text-sm">
                    <span class="font-medium text-blue-600">В работе</span> ·
                    <span class="font-medium text-green-600">230 000 ₽</span>
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="/test"
                        class="flex-1 text-center justify-center gap-2 bg-orange-50 text-orange-600 px-5 py-3 rounded-xl hover:bg-orange-100 transition-colors border border-orange-200 font-medium">
                        Открыть
                    </a>

                </div>

            </div>
        @endfor

    </div>

</div>

</body>
</html>
