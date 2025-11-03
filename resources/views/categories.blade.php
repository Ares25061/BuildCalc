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
<div class="max-w-7xl mx-auto py-12 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 justify-center mx-auto">

        @forelse($categories as $category)
            <a href="{{ url('/categories/' . ($category->slug ?? str_replace(' ', '_', $category->name))) }}"
               class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1 flex flex-col h-full">
                <div class="h-40 bg-gray-50 flex items-center justify-center p-4">
                    <img src="{{ $category->image_url }}"
                         alt="{{ $category->name }}"
                         class="h-full object-contain group-hover:scale-110 transition duration-300"
                         onerror="this.src='https://via.placeholder.com/300x200?text={{ urlencode($category->name) }}'"/>
                </div>
                <div class="p-6 flex-grow">
                    <h3 class="text-xl font-semibold text-gray-900 group-hover:text-orange-500 transition">
                        {{ $category->name }}
                    </h3>
                    <p class="text-gray-600 text-sm mt-2">
                        @if(isset($category->materials_count) && $category->materials_count > 0)
                            {{ $category->materials_count }} материалов
                        @else
                            Материалы скоро появятся
                        @endif
                    </p>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500 text-lg">
                    Категории не найдены
                </div>
                <p class="text-gray-400 mt-2">
                    Запустите парсер для получения категорий с OBI
                </p>
                <div class="mt-4">
                    <a href="/" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                        На главную
                    </a>
                </div>
            </div>
        @endforelse

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
