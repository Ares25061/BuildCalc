<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Навигационная панель -->
    @include('layouts.nav')

    <!-- Основной контент -->
        <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8
        <div class="max-w-lg w-full space-y-8">

            <form id="registerForm" class="mt-8 space-y-6 bg-white p-8 rounded-xl shadow-lg border border-gray-200" >
                <h2 class="text-3xl text-center font-bold text-gray-900">Создать аккаунт</h2>
                @csrf

                <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>
                <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"></div>

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
    </div>
</body>


<script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        try {
            const response = await fetch('/api/register', {
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

                showMessage('success', 'Аккаунт создан.');

                setTimeout(() => {
                    window.location.href = '/';
                }, 1000);
            } else {
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join(', ');
                    showMessage('error', errorMessages);
                } else {
                    showMessage('error', data.message || 'Ты ошибка');
                }
            }
        } catch (error) {
            showMessage('error', 'Попробуйте снова.');
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
