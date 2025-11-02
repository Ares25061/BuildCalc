<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог материалов - MaterialHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

@include('layouts.nav')

<!-- Заголовок -->
<div class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold text-gray-900">Каталог строительных материалов</h1>
        <p class="text-gray-600 mt-2">Выберите категорию материала для расчёта и составления сметы</p>
    </div>
</div>

<!-- Категории -->
<div class="max-w-7xl mx-auto py-12 px-4 ">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 justify-center mx-auto">


    <!-- Кирпич -->
        <a href="/categories/brick/materials"
           class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1 flex flex-col h-full">
            <div class="h-40 bg-gray-50 flex items-center justify-center p-4">
                <img src="https://i.freza.co/diygoods/52792/kirpich_oblitsovochniy_odinarniy_m150_1_pic.jpg"
                     class="h-full object-contain group-hover:scale-110 transition duration-300"/>
            </div>
            <div class="p-6 flex-grow">
                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-orange-500 transition">Кирпичи</h3>
                <p class="text-gray-600 text-sm mt-2">Керамический, облицовочный и др.</p>
            </div>
        </a>

        <!-- Обои -->
        <a href="/materials/wallpaper"
           class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1 flex flex-col h-full">
            <div class="h-40 bg-gray-50 flex items-center justify-center p-4">
                <img src="https://avatars.mds.yandex.net/i?id=f8d14db81b1683f72dcde990a1a8e475_l-5285824-images-thumbs&n=13"
                     class="h-full object-contain group-hover:scale-110 transition duration-300"/>
            </div>
            <div class="p-6 flex-grow">
                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-orange-500 transition">Обои</h3>
                <p class="text-gray-600 text-sm mt-2">Флизелиновые, виниловые, бумажные</p>
            </div>
        </a>

        <!-- Шпатлевка -->
        <a href="/materials/putty"
           class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1 flex flex-col h-full">
            <div class="h-40 bg-gray-50 flex items-center justify-center p-4">
                <img src="https://www.lavon-shop.ru/wa-data/public/shop/categories/48/61.jpg"
                     class="h-full object-contain group-hover:scale-110 transition duration-300"/>
            </div>
            <div class="p-6 flex-grow">
                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-orange-500 transition">Шпатлевка</h3>
                <p class="text-gray-600 text-sm mt-2">Стартовая, финишная, универсальная</p>
            </div>
        </a>

        <!-- Ламинат -->
        <a href="/materials/laminate"
           class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1 flex flex-col h-full">
            <div class="h-40 bg-gray-50 flex items-center justify-center p-4">
                <img src="https://avatars.mds.yandex.net/i?id=329bf69b8ccb9032b354ab362bd361f7_l-5603489-images-thumbs&n=13"
                     class="h-full object-contain group-hover:scale-110 transition duration-300"/>
            </div>
            <div class="p-6 flex-grow">
                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-orange-500 transition">Ламинат</h3>
                <p class="text-gray-600 text-sm mt-2">Напольные покрытия</p>
            </div>
        </a>

        <!-- Краска -->
        <a href="/materials/paint"
           class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1 flex flex-col h-full">
            <div class="h-40 bg-gray-50 flex items-center justify-center p-4">
                <img src="https://avatars.mds.yandex.net/i?id=9b8bb59788106aac9a5dc186c77a33e6bb57f285-3071255-images-thumbs&n=13"
                     class="h-full object-contain group-hover:scale-110 transition duration-300"/>
            </div>
            <div class="p-6 flex-grow">
                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-orange-500 transition">Краска</h3>
                <p class="text-gray-600 text-sm mt-2">Интерьерная, фасадная, акриловая</p>
            </div>
        </a>

        <!-- Плитка -->
        <a href="/materials/tile"
           class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1 flex flex-col h-full">
            <div class="h-40 bg-gray-50 flex items-center justify-center p-4">
                <img src="https://avatars.mds.yandex.net/i?id=693837bffcd64b1ab70940b977a95af9e824a1bf-10089679-images-thumbs&n=13"
                     class="h-full object-contain group-hover:scale-110 transition duration-300"/>
            </div>
            <div class="p-6 flex-grow">
                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-orange-500 transition">Плитка</h3>
                <p class="text-gray-600 text-sm mt-2">Керамическая и керамогранит</p>
            </div>
        </a>

        <a href="/materials/cement"
           class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1 flex flex-col h-full">
            <div class="h-40 bg-gray-50 flex items-center justify-center p-4">
                <img src="https://cdnstatic.rg.ru/uploads/attachments/article/183/65/75/0002.jpg"
                     class="h-full object-contain group-hover:scale-110 transition duration-300"/>
            </div>
            <div class="p-6 flex-grow">
                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-orange-500 transition">Цемент</h3>
                <p class="text-gray-600 text-sm mt-2">Смеси и сыпучие материалы</p>
            </div>
        </a>


    </div>
</div>

<footer class="bg-gray-900 text-gray-400 py-8">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <div class="mb-4">
            <h3 class="text-white font-semibold mb-2 text-lg">MaterialHub</h3>
            <p class="text-sm">Профессиональный сервис для расчета строительных смет и подбора материалов</p>
        </div>
    </div>
</footer>

</body>
</html>
