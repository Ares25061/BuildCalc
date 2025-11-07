<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaterialHub - Профессиональные строительные сметы</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
@include('layouts.nav')

<section class="bg-gradient-to-r from-gray-700 to-gray-900 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">Индустриальный подход к сметам</h1>
        <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto text-gray-300">
            Технологичные решения для строительных расчетов и ведения смет
        </p>
        <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-4">

            <a href="/categories" class="border-2 border-gray-400 text-gray-200 px-8 py-4 rounded-lg font-semibold hover:bg-gray-400 hover:text-gray-900 transition duration-200 text-lg">
                Каталог материалов
            </a>
            <a href="#" class="bg-orange-500 text-white px-8 py-4 rounded-lg font-semibold hover:bg-orange-600 transition duration-200 text-lg">
                Создать смету
            </a>
        </div>
    </div>
</section>
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Почему строители выбирают нас?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-8 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-lg transition duration-200">
                <div class="bg-gray-200 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">📊</span>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Автоматический расчет</h3>
                <p class="text-gray-600 mb-4">Точные расчеты смет с учетом всех норм расхода материалов</p>
            </div>

            <div class="text-center p-8 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-lg transition duration-200">
                <div class="bg-gray-200 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">🛒</span>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-gray-800">База материалов</h3>
                <p class="text-gray-600 mb-4">Доступ к актуальным ценам от поставщиков строительных материалов</p>
            </div>

            <div class="text-center p-8 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-lg transition duration-200">
                <div class="bg-gray-200 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">🕐</span>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Экономия времени</h3>
                <p class="text-gray-600 mb-4">Автоматизация рутинных расчетов</p>
            </div>
        </div>
    </div>
</section>
<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Как работает сервис?</h2>
        <div class="grid md:grid-cols-4 gap-6">
            <div class="text-center bg-white p-6 rounded-lg shadow-sm">
                <div class="bg-orange-500 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 font-bold text-lg">1</div>
                <h3 class="font-semibold mb-2 text-gray-800">Создайте проект</h3>
                <p class="text-sm text-gray-600">Добавьте данные о ремонте или строительстве</p>
            </div>
            <div class="text-center bg-white p-6 rounded-lg shadow-sm">
                <div class="bg-orange-500 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 font-bold text-lg">2</div>
                <h3 class="font-semibold mb-2 text-gray-800">Выберите работы</h3>
                <p class="text-sm text-gray-600">Укажите виды ремонтных и строительных работ</p>
            </div>
            <div class="text-center bg-white p-6 rounded-lg shadow-sm">
                <div class="bg-orange-500 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 font-bold text-lg">3</div>
                <h3 class="font-semibold mb-2 text-gray-800">Рассчитайте материалы</h3>
                <p class="text-sm text-gray-600">Автоматический подсчет и подбор материалов</p>
            </div>
            <div class="text-center bg-white p-6 rounded-lg shadow-sm">
                <div class="bg-orange-500 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 font-bold text-lg">4</div>
                <h3 class="font-semibold mb-2 text-gray-800">Получите смету</h3>
                <p class="text-sm text-gray-600">Готовая документация и отчетность</p>
            </div>
        </div>
    </div>
</section>
<footer class="bg-gray-900 text-gray-400 py-8">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <div class="mb-4">
            <h3 class="text-white font-semibold mb-2 text-lg">MaterialHub</h3>
            <p class="text-sm">Профессиональный сервис для расчета строительных смет и подбора материалов</p>
        </div>
    </div>
</footer>

<script>
    window.addEventListener('load', function() {
        const token = localStorage.getItem('auth_token');
        const guestButtons = document.getElementById('guestButtons');
        const userButtons = document.getElementById('userButtons');

        if (token) {
            guestButtons.classList.add('hidden');
            userButtons.classList.remove('hidden');
        } else {
            guestButtons.classList.remove('hidden');
            userButtons.classList.add('hidden');
        }
    });

    async function logout() {
        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '/';
            } else {
                console.error('Logout failed');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '/';
            }
        } catch (error) {
            console.error('Logout error:', error);
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            window.location.href = '/';
        }
    }
</script>
</body>
</html>
