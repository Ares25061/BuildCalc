<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
<div class="max-w-md w-full space-y-8">
    <div class="text-center">
        <a href="/" class="flex items-center justify-center space-x-3 mb-8">
            <span class="text-3xl">🔧</span>
            <span class="text-2xl font-bold text-gray-900">MaterialHub</span>
        </a>
    </div>

    <form id="loginForm" class="mt-8 space-y-6 bg-white p-8 rounded-xl shadow-lg border border-gray-200">
        <h2 class="text-3xl text-center font-bold text-gray-900">Вход в аккаунт</h2>
        @csrf

        <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>
        <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"></div>

        <div class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Почта</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                       class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Пароль</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                       class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
            </div>
        </div>

        <div class="flex justify-end">
            <div class="text-sm">
                <a href="#" class="font-medium text-orange-500 hover:text-orange-600 transition duration-200">
                    Забыли пароль?
                </a>
            </div>
        </div>

        <div>
            <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200">
                Войти
            </button>
        </div>

        <div class="text-center">
            <p class="text-sm text-gray-600">
                Нет аккаунта?
                <a href="/register" class="font-medium text-orange-500 hover:text-orange-600 transition duration-200">
                    Зарегистрироваться
                </a>
            </p>
        </div>
    </form>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                localStorage.setItem('auth_token', data.authorization.token);
                localStorage.setItem('user', JSON.stringify(data.user));

                showMessage('success', 'Успешный вход.');

                setTimeout(() => {
                    window.location.href = '/';
                }, 1000);
            } else {
                showMessage('error', data.message || 'Ошибка входа');
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('error', 'Что-то поломалось.');
        }
    });

    function showMessage(type, message) {
        const errorDiv = document.getElementById('errorMessage');
        const successDiv = document.getElementById('successMessage');

        if (type === 'error') {
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
            successDiv.classList.add('hidden');
        } else {
            successDiv.textContent = message;
            successDiv.classList.remove('hidden');
            errorDiv.classList.add('hidden');
        }
    }
    // check if already logged in
    window.addEventListener('load', function() {
        const token = localStorage.getItem('auth_token');
        if (token) {
            window.location.href = '/dashboard';
        }
    });
</script>
</body>
</html>


