<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
<div class="max-w-md w-full space-y-8">
    <div class="text-center">
        <a href="/" class="flex items-center justify-center space-x-1 mb-8">
            <span class="text-3xl">🔧</span>
            <span class="text-2xl font-bold text-gray-900">MaterialHub</span>
        </a>
    </div>
    <form class="mt-8 space-y-6 bg-white p-8 rounded-xl shadow-lg border border-gray-200" action="#" method="POST">
        <h2 class="text-3xl text-center font-bold text-gray-900">Создать аккаунт</h2>
        @csrf
        <div class="space-y-4">

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                <input id="name" name="name" type="text" autocomplete="name" required
                       class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Почта</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                       class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Пароль</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required
                       class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                <p class="mt-1 text-xs text-gray-500">Пароль должен содержать минимум 8 символов</p>
            </div>

        </div>

        <div>
            <button type="submit"
                    class="w-full py-3 px-4 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition duration-200">
                Создать аккаунт
            </button>
        </div>

        <div class="text-center">
            <p class="text-sm text-gray-600">
                Уже есть аккаунт?
                <a href="/login" class="font-medium text-orange-500 hover:text-orange-600 transition duration-200">
                    Войти
                </a>
            </p>
        </div>
    </form>
</div>
</body>
</html>
