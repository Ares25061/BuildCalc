<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание сметы — MaterialHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

@include('layouts.nav')

<div class="max-w-4xl mx-auto px-4 py-10">

    <!-- Заголовок -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Создание новой сметы</h1>
        <p class="text-gray-600 mt-2">Заполните основную информацию о проекте</p>
    </div>

    <!-- Форма создания -->
    <div class="bg-white p-8 rounded-2xl shadow border border-gray-200">
        <form id="createProjectForm">
            <!-- Название проекта -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Название сметы *
                </label>
                <input type="text" id="name" name="name" required
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition"
                       placeholder="Например: Ремонт кухни, Строительство гаража..."
                       maxlength="255">
                <p class="text-red-500 text-sm mt-1 hidden" id="nameError"></p>
            </div>

            <!-- Описание -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Описание
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition"
                          placeholder="Дополнительная информация о проекте..."></textarea>
            </div>

            <!-- Статус -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Статус
                </label>
                <select id="status" name="status"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
                    <option value="draft">Черновик</option>
                    <option value="in_progress">В работе</option>
                    <option value="completed">Готово</option>
                </select>
            </div>

            <!-- Предварительная стоимость -->
            <div class="mb-8">
                <label for="total_estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">
                    Предварительная стоимость (₽)
                </label>
                <input type="number" id="total_estimated_cost" name="total_estimated_cost" step="0.01" min="0"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition"
                       placeholder="0.00">
            </div>

            <!-- Кнопки -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="/project"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center font-medium">
                    Отмена
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition font-medium flex items-center justify-center gap-2">
                    <svg id="loadingSpinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="submitText">Создать смету</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    document.getElementById('createProjectForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitButton = e.target.querySelector('button[type="submit"]');
        const submitText = document.getElementById('submitText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const nameError = document.getElementById('nameError');

        // Сбрасываем ошибки
        nameError.classList.add('hidden');

        // Получаем данные формы
        const formData = {
            name: document.getElementById('name').value.trim(),
            description: document.getElementById('description').value.trim(),
            status: document.getElementById('status').value,
            total_estimated_cost: document.getElementById('total_estimated_cost').value || 0
        };

        // Валидация
        if (!formData.name) {
            nameError.textContent = 'Название сметы обязательно';
            nameError.classList.remove('hidden');
            return;
        }

        // Показываем загрузку
        submitText.textContent = 'Создание...';
        loadingSpinner.classList.remove('hidden');
        submitButton.disabled = true;

        try {
            const token = localStorage.getItem('auth_token');

            if (!token) {
                throw new Error('Необходима авторизация');
            }

            const response = await fetch('/api/projects', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok) {
                // Успешное создание - перенаправляем на страницу сметы
                showSuccess('Смета успешно создана!');
                setTimeout(() => {
                    window.location.href = `/project/${result.id}`;
                }, 1000);
            } else {
                // Обработка ошибок
                if (result.errors && result.errors.name) {
                    nameError.textContent = result.errors.name[0];
                    nameError.classList.remove('hidden');
                } else {
                    throw new Error(result.message || 'Ошибка при создании сметы');
                }
            }

        } catch (error) {
            console.error('Error creating project:', error);
            showError(error.message || 'Произошла ошибка при создании сметы');
        } finally {
            // Восстанавливаем кнопку
            submitText.textContent = 'Создать смету';
            loadingSpinner.classList.add('hidden');
            submitButton.disabled = false;
        }
    });

    function showSuccess(message) {
        // Создаем уведомление об успехе
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    function showError(message) {
        // Создаем уведомление об ошибке
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Проверка авторизации при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/login';
        }
    });
</script>

</body>
</html>
