<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сброс пароля</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
@include('layouts.nav')

<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <form id="resetPasswordForm" class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-3xl text-center font-bold text-gray-900 mb-6">Сброс пароля</h2>

            <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>
            <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"></div>

            <input type="hidden" id="token" name="token" value="{{ $token }}">

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Почта</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200"
                           value="{{ request()->email }}">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Новый пароль</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                           class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                    <p class="mt-1 text-xs text-gray-500">Пароль должен содержать минимум 8 символов</p>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Подтвердите пароль</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                           class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200">
                    Сбросить пароль
                </button>
            </div>

            <div class="text-center mt-4">
                <a href="/login" class="font-medium text-orange-500 hover:text-orange-600 transition duration-200">
                    Вернуться к входу
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
            token: document.getElementById('token').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value
        };

        try {
            const response = await fetch('/api/reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                showMessage('success', data.message);
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showMessage('error', data.message);
            }
        } catch (error) {
            showMessage('error', 'Ошибка сброса пароля');
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

    window.addEventListener('load', function() {
        const token = localStorage.getItem('auth_token');
        if (token) {
            window.location.href = '/';
        }
    });
</script>
</body>
</html>
