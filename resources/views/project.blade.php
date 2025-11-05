<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои сметы — MaterialHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

@include('layouts.nav')

<div class="max-w-7xl mx-auto px-4 py-10">

    <!-- Заголовок -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Мои сметы</h1>
            <p class="text-gray-600 mt-2 text-sm">Все ваши расчёты в одном месте</p>
        </div>

        <button onclick="createNewProject()"
                class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-medium shadow transition">
            + Создать смету
        </button>
    </div>

    <!-- Фильтры -->
    <div class="bg-white p-5 rounded-xl shadow border border-gray-200 mb-10 flex flex-wrap gap-4 items-center justify-between">

        <input type="text" id="searchInput"
               placeholder="Поиск по названию..."
               class="w-full md:w-1/3 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none"
               oninput="debounce(filterProjects, 500)">

        <div class="flex gap-2">
            <button class="filter-btn active bg-orange-50 text-orange-600 px-4 py-2 rounded-lg text-sm shadow-sm"
                    data-status="all" onclick="setFilter('all')">
                Все
            </button>
            <button class="filter-btn bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm"
                    data-status="in_progress" onclick="setFilter('in_progress')">
                В работе
            </button>
            <button class="filter-btn bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm"
                    data-status="completed" onclick="setFilter('completed')">
                Готовые
            </button>
            <button class="filter-btn bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm"
                    data-status="draft" onclick="setFilter('draft')">
                Черновики
            </button>
        </div>

        <select id="sortSelect" class="border rounded-lg px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100" onchange="sortProjects()">
            <option value="date_desc">Сортировать: по дате (новые)</option>
            <option value="date_asc">Сортировать: по дате (старые)</option>
            <option value="cost_desc">Сортировать: по стоимости (убыв.)</option>
            <option value="cost_asc">Сортировать: по стоимости (возр.)</option>
            <option value="name_asc">Сортировать: по названию (А-Я)</option>
            <option value="name_desc">Сортировать: по названию (Я-А)</option>
        </select>
    </div>

    <!-- Контейнер для проектов -->
    <div id="projectsContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-7">
        <!-- Проекты будут загружаться здесь -->
        <div id="loadingMessage" class="col-span-full text-center py-10">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto"></div>
            <p class="text-gray-600 mt-4">Загрузка смет...</p>
        </div>
    </div>

    <!-- Сообщение если нет проектов -->
    <div id="noProjectsMessage" class="hidden text-center py-10">
        <div class="bg-white p-8 rounded-2xl border border-gray-200">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">У вас пока нет смет</h3>
            <p class="text-gray-600 mb-4">Создайте свою первую смету, чтобы начать работу</p>
            <button onclick="createNewProject()"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium">
                Создать первую смету
            </button>
        </div>
    </div>

</div>

<script>
    let allProjects = [];
    let currentFilter = 'all';
    let currentSearch = '';
    let currentSort = 'date_desc';
    let debounceTimer;

    // Дебаунс для поиска
    function debounce(func, wait) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(func, wait);
    }

    // Загрузка проектов при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        loadProjects();
    });

    async function loadProjects() {
        const token = localStorage.getItem('auth_token');

        if (!token) {
            window.location.href = '/login';
            return;
        }

        try {
            // Показываем сообщение о загрузке
            showLoading();

            // Строим URL с параметрами фильтрации
            const params = new URLSearchParams();

            if (currentFilter !== 'all') {
                params.append('status', currentFilter);
            }

            if (currentSearch) {
                params.append('search', currentSearch);
            }

            if (currentSort) {
                params.append('sort', currentSort);
            }

            const url = `/api/projects?${params.toString()}`;

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const projects = await response.json();
                allProjects = projects;
                renderProjects();
            } else if (response.status === 401) {
                // Не авторизован - перенаправляем на логин
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            } else {
                console.error('Failed to load projects');
                showError('Не удалось загрузить сметы');
            }
        } catch (error) {
            console.error('Error loading projects:', error);
            showError('Ошибка при загрузке смет');
        }
    }

    function showLoading() {
        const container = document.getElementById('projectsContainer');
        const noProjectsMessage = document.getElementById('noProjectsMessage');

        // Скрываем сообщение "нет проектов"
        if (noProjectsMessage) {
            noProjectsMessage.classList.add('hidden');
        }

        // Показываем индикатор загрузки
        container.innerHTML = `
            <div id="loadingMessage" class="col-span-full text-center py-10">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto"></div>
                <p class="text-gray-600 mt-4">Загрузка смет...</p>
            </div>
        `;
    }

    function renderProjects() {
        const container = document.getElementById('projectsContainer');
        const noProjectsMessage = document.getElementById('noProjectsMessage');

        if (!container) {
            console.error('Projects container not found');
            return;
        }

        // Очищаем контейнер
        container.innerHTML = '';

        if (allProjects.length === 0) {
            // Показываем сообщение "нет проектов"
            if (noProjectsMessage) {
                noProjectsMessage.classList.remove('hidden');
            }
            return;
        }

        // Скрываем сообщение "нет проектов"
        if (noProjectsMessage) {
            noProjectsMessage.classList.add('hidden');
        }

        // Генерируем HTML для проектов
        const projectsHTML = allProjects.map(project => `
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow hover:shadow-lg transition flex flex-col">
                <h3 class="text-lg font-semibold text-gray-900">${escapeHtml(project.name)}</h3>
                <p class="text-gray-500 text-sm mt-1">${escapeHtml(project.description || 'Без описания')}</p>

                <div class="mt-4 flex justify-between text-sm">
                    <span class="px-3 py-1 rounded-full ${getStatusClass(project.status)}">
                        ${getStatusText(project.status)}
                    </span>
                    <span class="font-semibold ${getCostColor(project.total_estimated_cost)}">
                        ${formatCurrency(project.total_estimated_cost)}
                    </span>
                </div>

                <div class="mt-2 text-xs text-gray-500">
                    Обновлено: ${formatDate(project.updated_at)}
                </div>

                <a href="/project/${project.id}"
                   class="mt-6 text-center bg-orange-50 text-orange-600 px-5 py-3 rounded-xl border hover:bg-orange-100 transition">
                    Открыть
                </a>
            </div>
        `).join('');

        container.innerHTML = projectsHTML;
    }

    function setFilter(status) {
        currentFilter = status;

        // Обновляем активные кнопки
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-orange-50', 'text-orange-600', 'shadow-sm');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });

        const activeBtn = document.querySelector(`[data-status="${status}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
            activeBtn.classList.add('bg-orange-50', 'text-orange-600', 'shadow-sm');
        }

        loadProjects(); // Перезагружаем проекты с новым фильтром
    }

    function filterProjects() {
        currentSearch = document.getElementById('searchInput').value;
        loadProjects(); // Перезагружаем проекты с новым поисковым запросом
    }

    function sortProjects() {
        currentSort = document.getElementById('sortSelect').value;
        loadProjects(); // Перезагружаем проекты с новой сортировкой
    }

    function createNewProject() {
        window.location.href = '/project/create';
    }

    // Вспомогательные функции
    function getStatusClass(status) {
        switch (status) {
            case 'in_progress': return 'bg-blue-100 text-blue-700';
            case 'completed': return 'bg-green-100 text-green-700';
            case 'draft': return 'bg-gray-200 text-gray-700';
            default: return 'bg-gray-100 text-gray-700';
        }
    }

    function getStatusText(status) {
        switch (status) {
            case 'in_progress': return 'В работе';
            case 'completed': return 'Готово';
            case 'draft': return 'Черновик';
            default: return status;
        }
    }

    function getCostColor(cost) {
        return cost > 0 ? 'text-green-600' : 'text-gray-800';
    }

    function formatCurrency(amount) {
        if (!amount) return '0 ₽';
        return new Intl.NumberFormat('ru-RU').format(amount) + ' ₽';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('ru-RU');
    }

    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function showError(message) {
        const container = document.getElementById('projectsContainer');
        const noProjectsMessage = document.getElementById('noProjectsMessage');

        // Скрываем сообщение "нет проектов"
        if (noProjectsMessage) {
            noProjectsMessage.classList.add('hidden');
        }

        if (container) {
            container.innerHTML = `
                <div class="col-span-full text-center py-10">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 max-w-md mx-auto">
                        <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-red-800 mb-2">Ошибка</h3>
                        <p class="text-red-600">${message}</p>
                        <button onclick="loadProjects()" class="mt-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                            Попробовать снова
                        </button>
                    </div>
                </div>
            `;
        }
    }

    // Функция для быстрого создания проекта
    async function createQuickProject() {
        const projectName = prompt('Введите название сметы:');

        if (!projectName || !projectName.trim()) {
            return;
        }

        const token = localStorage.getItem('auth_token');

        if (!token) {
            window.location.href = '/login';
            return;
        }

        try {
            const response = await fetch('/api/projects', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: projectName.trim(),
                    status: 'draft'
                })
            });

            if (response.ok) {
                const project = await response.json();
                showSuccess('Смета создана!');
                // Перезагружаем список проектов
                loadProjects();
            } else {
                const error = await response.json();
                throw new Error(error.message || 'Ошибка при создании сметы');
            }
        } catch (error) {
            console.error('Error creating project:', error);
            showError(error.message);
        }
    }

    function showSuccess(message) {
        // Создаем временное уведомление
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>

</body>
</html>
