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
    <div class="bg-white p-6 rounded-2xl shadow border border-gray-200">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Ремонт кухни</h1>
                <p class="text-gray-500 mt-1 text-sm">Создано: 12.10.2025</p>
            </div>

            <div class="flex gap-3">
                <button class="bg-blue-50 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-100">
                    📄 Экспорт PDF
                </button>
                <button class="bg-gray-50 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100">
                    ✎ Редактировать
                </button>
                <button class="bg-red-50 text-red-500 px-4 py-2 rounded-lg hover:bg-red-100">
                    ✕ Удалить
                </button>
            </div>
        </div>

        <div class="mt-4 flex gap-3 text-sm">
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full">
                В процессе
            </span>

            <span class="text-gray-700">
                Итог: <span class="font-semibold text-green-600">230 000 ₽</span>
            </span>
        </div>
    </div>


    <!-- ✅ Таблица позиций -->
    <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">

        <!-- Header -->
        <div class="grid grid-cols-12 bg-gray-50 text-gray-600 text-xs font-medium px-4 py-3">
            <div class="col-span-6">Название</div>
            <div class="col-span-2 text-center">Кол-во</div>
            <div class="col-span-2 text-right">Цена</div>
            <div class="col-span-2 text-right">Сумма</div>
        </div>

        <!-- Items -->
        @for($i = 0; $i < 3; $i++)
        <div class="grid grid-cols-12 px-4 py-4 border-t text-sm items-center">

            <div class="col-span-6 font-medium">Кирпич керамический</div>

            <div class="col-span-2 text-center">
                <input type="number" value="200"
                       class="w-20 text-center border rounded-lg p-1 focus:ring-orange-500">
            </div>

            <div class="col-span-2 text-right text-gray-800">24 ₽</div>
            <div class="col-span-2 text-right font-bold text-gray-900">4 800 ₽</div>

        </div>
        @endfor

    </div>

    <!-- ✅ Панель итогов -->
    <div class="flex justify-end">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow text-right w-full md:w-1/3">

            <div class="text-gray-600 text-sm mb-2">Материалы:</div>
            <div class="text-lg font-semibold text-gray-900 mb-4">180 000 ₽</div>

            <div class="text-gray-600 text-sm mb-2">Работы:</div>
            <div class="text-lg font-semibold text-gray-900 mb-4">50 000 ₽</div>

            <hr class="my-3">

            <div class="text-xl font-bold text-green-600">
                Итого: 230 000 ₽
            </div>

        </div>
    </div>

</div>

</body>
</html>

