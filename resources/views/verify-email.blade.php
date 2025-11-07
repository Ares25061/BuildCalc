<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение почты</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
@include('layouts.nav')

<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-3xl text-center font-bold text-gray-900 mb-6">Подтверждение почты</h2>

            <div id="message" class="hidden mb-4 p-4 rounded"></div>

            @if (session('verified'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    Ваш email успешно подтвержден!
                </div>
            @endif

            <div class="text-center">
                <p class="text-gray-600 mb-4">Пожалуйста, подтвердите вашу почту для полного доступа к аккаунту.</p>
                <p class="text-gray-500 text-sm mb-6">Письмо с ссылкой для подтверждения было отправлено на вашу почту.</p>

                <div class="space-y-4">
                    <button onclick="resendVerificationEmail()"
                            class="w-full bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition duration-200 font-medium">
                        Отправить письмо повторно
                    </button>

                    <button onclick="skipVerification()"
                            class="w-full bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-200 font-medium">
                        Подтвердить позже
                    </button>
                </div>

                <div class="mt-6">
                    <a href="/" class="text-orange-500 hover:text-orange-600 transition duration-200">
                        Вернуться на главную
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function resendVerificationEmail() {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await fetch('/api/email/verification-notification', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            const messageDiv = document.getElementById('message');
            messageDiv.classList.remove('hidden');

            if (response.ok) {
                messageDiv.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700');
                messageDiv.textContent = data.message;
            } else {
                messageDiv.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700');
                messageDiv.textContent = data.message || 'Ошибка отправки письма';
            }
        } catch (error) {
            console.error('Error:', error);
            const messageDiv = document.getElementById('message');
            messageDiv.classList.remove('hidden');
            messageDiv.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700');
            messageDiv.textContent = 'Ошибка отправки письма';
        }
    }

    function skipVerification() {
        window.location.href = '/';
    }

    window.addEventListener('load', async function() {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        try {
            const response = await fetch('/api/user', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const user = await response.json();
                if (user.email_verified_at) {
                    window.location.href = '/';
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
</script>
</body>
</html>
