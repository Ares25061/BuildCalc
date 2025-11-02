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
        <aside
            class="bg-white p-6 shadow-md rounded-2xl border border-gray-200 space-y-4 h-fit  top-6 self-start <!-- sticky--> ">


            <h2 class="text-lg font-semibold text-gray-900 text-center">
                Фильтры
            </h2>

            <!-- Название -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Название</label>
                <input type="text"
                       class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
            </div>

            <!-- Бренд -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Бренд</label>
                <select
                    class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                    <option>Все</option>
                    <option>Braer</option>
                    <option>Rauf</option>
                    <option>LegoBrick</option>
                </select>
            </div>

            <!-- Цвет -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Цвет</label>
                <select
                    class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                    <option>Любой</option>
                    <option>Красный</option>
                    <option>Серый</option>
                    <option>Белый</option>
                </select>
            </div>

            <!-- Цена -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Цена, ₽</label>
                <div class="flex gap-2 mt-1">
                    <input type="number" placeholder="от"
                           class="w-1/2 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                    <input type="number" placeholder="до"
                           class="w-1/2 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 outline-none">
                </div>
            </div>

            <!-- Кнопка -->
            <button
                class="bg-orange-400 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                Применить
            </button>

        </aside>


        <!-- ✅ Карточки — больше пространства и ровные 3 в ряд -->
        <section>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @for ($i = 0; $i < 9; $i++)
                    <div class="bg-white rounded-xl border border-gray-200 shadow hover:shadow-xl transition
                                flex flex-col overflow-hidden w-full min-h-[430px]">

                        <!-- Фото -->
                        <div class="h-40 bg-white flex items-center justify-center p-4">
                            <img src="https://i.freza.co/diygoods/52792/kirpich_oblitsovochniy_odinarniy_m150_1_pic.jpg"
                                 class="max-h-full object-contain">
                        </div>

                        <!-- Контент -->
                        <div class="p-4 text-sm text-gray-700 flex flex-col gap-1 flex-grow">

                            <h3 class="font-semibold text-lg text-gray-900 h-12">
                                Кирпич керамический
                            </h3>
                            <p class="text-gray-500">КК-250-120-65</p>

                            <div class="flex justify-between"><span>Размер:</span><span>250×120×65</span></div>
                            <div class="flex justify-between"><span>Вес:</span><span>3.6 кг</span></div>
                            <div class="flex justify-between"><span>Цвет:</span><span>Красный</span></div>

                            <p class="text-xl font-bold text-gray-900 mt-auto">24₽ /шт</p>

                            <!-- Количество -->
                            <div x-data="{ qty: 1 }"
                                 class="mt-3 mx-auto border border-gray-300 rounded-xl px-4 py-2 w-full flex items-center justify-between gap-3">

                                <button @click="qty = Math.max(1, qty - 1)"
                                        class="text-2xl leading-none text-gray-600 hover:text-black">–
                                </button>

                                <input type="number" min="1" x-model="qty"
                                       class="w-12 text-center outline-none bg-transparent text-lg font-medium border-0 focus:ring-0">

                                <span class="text-sm text-gray-700">шт.</span>

                                <button @click="qty++"
                                        class="text-2xl leading-none text-gray-600 hover:text-black">+
                                </button>
                            </div>

                            <div class="flex gap-2 mt-3">
                                <button
                                    class="bg-orange-500 text-white py-2 px-3 rounded-lg hover:bg-orange-600 transition flex-1 text-sm ">
                                    В смету
                                </button>
                                <button
                                    class="bg-gray-800 text-white py-2 px-3 rounded-lg hover:bg-gray-600 transition flex-1 text-sm border border-gray-300">
                                    Рассчитать
                                </button>
                            </div>

                        </div>
                    </div>
                @endfor
            </div>
        </section>

        <!-- ✅ Калькулятор кирпича-->
        <aside
            class="bg-white p-6 shadow-md rounded-2xl border border-gray-200 space-y-4 h-fit top-6 self-start <!-- sticky--> ">


            <h3 class="text-lg font-semibold text-gray-900 gap-2 text-center">
                Калькулятор
            </h3>

            <!-- Размер кирпича -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Размер кирпича</label>
                <select
                    class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <option>250×120×65</option>
                    <option>250×120×88</option>
                    <option>230×110×65</option>
                </select>
            </div>

            <!-- Длина стен -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Длина стен</label>
                <div class="relative mt-1">
                    <input type="number"
                           class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м</span>
                </div>
            </div>

            <!-- Высота стен -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Высота стен</label>
                <div class="relative mt-1">
                    <input type="number"
                           class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">м</span>
                </div>
            </div>

            <!-- Толщина стен -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Толщина стен</label>
                <select
                    class="w-full mt-1 border border-gray-300 rounded-lg p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
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
                    <input type="number"
                           class="w-full border border-gray-300 rounded-lg p-2.5 pr-10 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-xs">м²</span>
                </div>
            </div>

            <!-- Цена за штуку -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Цена за 1 шт</label>
                <div class="relative mt-1">
                    <input type="number"
                           class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">₽</span>
                </div>
            </div>

            <!-- Пустотность -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Пустотность кирпича</label>
                <div class="relative mt-1">
                    <input type="number"
                           class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">%</span>
                </div>
            </div>

            <!-- ✅ Кнопка -->
            <button
                class="bg-gray-800 hover:bg-orange-500 text-white w-full py-2.5 text-sm font-medium rounded-lg transition">
                Рассчитать
            </button>

        </aside>

    </div>
</body>
</html>
