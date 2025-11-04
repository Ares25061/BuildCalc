<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Смета — MaterialHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>

<body class="bg-gray-100">

@include('layouts.nav')

<div class="max-w-7xl mx-auto px-4 py-10 space-y-10">

    <!-- ✅ Улучшенная шапка проекта -->
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <!-- Левая часть: название и информация -->
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <h1 id="projectName" class="text-2xl lg:text-3xl font-bold text-gray-900">Загрузка...</h1>
                    <span id="projectStatusBadge" class="px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap">
                        Загрузка...
                    </span>
                    <button onclick="printEstimate()" class="flex items-center gap-2 px-4 py-2 font-medium text-gray-700 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Печать
                    </button>


                    <button onclick="editProject()" class="flex items-center justify-center gap-2 bg-gray-200 text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors border border-gray-300 font-medium text-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Редактировать
                    </button>

                    <button onclick="deleteProject()" class="flex items-center justify-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition-colors border border-red-200 font-medium text-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Удалить
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 mb-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Создано</p>
                            <p id="projectCreated" class="text-sm font-medium">Загрузка...</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Статус</p>
                            <p id="projectStatusText" class="text-sm font-medium">Загрузка...</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Позиций</p>
                            <p id="workPositionsCount" class="text-sm font-medium">0 позиций</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Бюджет</p>
                            <p id="projectBudget" class="text-sm font-medium text-green-600">0 ₽</p>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <!-- ✅ Бюджет проекта -->
    <div class="bg-white p-6 rounded-2xl shadow border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Бюджет проекта</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p id="totalBudget" class="text-2xl font-bold text-blue-600">0 ₽</p>
                <p class="text-sm text-gray-600 mt-1">Общий бюджет</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p id="materialsTotal" class="text-2xl font-bold text-green-600">0 ₽</p>
                <p class="text-sm text-gray-600 mt-1">Материалы</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p id="workPositionsTotal" class="text-2xl font-bold text-purple-600">0</p>
                <p class="text-sm text-gray-600 mt-1">Позиций работ</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                <p id="savings" class="text-2xl font-bold text-green-600">0 ₽</p>
                <p class="text-sm text-gray-600 mt-1">Экономия</p>
            </div>
        </div>
    </div>

    <div class="bg-gray-100">
        <!-- ✅ Контейнер для позиций работ -->
        <div id="workPositionsContainer">
            <!-- Позиции работ будут загружаться здесь -->
            <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
                <div class="px-6 py-8 text-center text-gray-500">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 mx-auto mb-2"></div>
                    <p>Загрузка позиций работ...</p>
                </div>
            </div>
        </div>

        <!-- ✅ Улучшенная панель добавления материалов -->
        <div class="flex flex-col sm:flex-row justify-between items-center p-4 border-t bg-white rounded-2xl shadow border border-gray-200 gap-4">
            <div class="text-sm text-gray-600">
                Всего позиций: <span id="totalWorkPositionsCount" class="font-medium">0</span> •
                Материалов: <span id="totalMaterialsCount" class="font-medium">0</span>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="/categories?project_id={{ $projectId }}"
                   class="flex items-center justify-center gap-2 bg-orange-50 text-orange-600 px-5 py-2 rounded-xl hover:bg-orange-100 transition-colors border border-orange-200 font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Добавить материал
                </a>
                <button onclick="addWorkPosition()"
                        class="flex items-center justify-center gap-2 bg-blue-50 text-blue-600 px-5 py-2 rounded-xl hover:bg-blue-100 transition-colors border border-blue-200 font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Новая позиция работ
                </button>
            </div>
        </div>
    </div>


    <!-- ✅ Панель итогов -->
    <div class="flex justify-end">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow text-right w-full md:w-1/3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Финансовый итог</h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Стоимость материалов:</span>
                    <span id="materialsSubtotal" class="font-semibold text-gray-900">0 ₽</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Стоимость работ:</span>
                    <span class="font-semibold text-gray-900">0 ₽</span>
                </div>

                <hr class="my-3 border-gray-300">

                <div class="flex justify-between items-center text-xl font-bold text-green-600">
                    <span>Итого:</span>
                    <span id="grandTotal">0 ₽</span>
                </div>

                <div id="budgetStatus" class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200 hidden">
                    <div class="flex items-center gap-2 text-sm text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">В рамках бюджета</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Модальное окно редактирования проекта -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Редактировать смету</h3>
        <form id="editProjectForm">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название</label>
                    <input type="text" id="editName" class="w-full px-3 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                    <textarea id="editDescription" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Статус</label>
                    <select id="editStatus" class="w-full px-3 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500">
                        <option value="draft">Черновик</option>
                        <option value="in_progress">В работе</option>
                        <option value="completed">Готово</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Бюджет (₽)</label>
                    <input type="number" id="editBudget" class="w-full px-3 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Отмена</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно добавления позиции работ -->
<div id="addWorkPositionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Добавить позицию работ</h3>
        <form id="addWorkPositionForm">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Тип работ</label>
                    <select id="workTypeSelect" class="w-full px-3 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" required>
                        <option value="">Выберите тип работ...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Описание (необязательно)</label>
                    <textarea id="workPositionNotes" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" placeholder="Например: Монтаж розеток в кухне..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeAddWorkPositionModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Отмена</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Добавить</button>
            </div>
        </form>
    </div>
</div>

<script>
    const projectId = {{ $projectId }};
    let projectData = null;
    let projectItems = [];
    let workTypes = [];

    // Загрузка данных при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        loadProjectData();
        loadWorkTypes();
        loadProjectItems();
    });

    // Загрузка данных проекта
    async function loadProjectData() {
        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch(`/api/projects/${projectId}`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                projectData = await response.json();
                displayProjectData();
            } else {
                throw new Error('Не удалось загрузить данные проекта');
            }
        } catch (error) {
            console.error('Error loading project:', error);
            showError('Ошибка при загрузке данных проекта');
        }
    }

    // Загрузка типов работ
    async function loadWorkTypes() {
        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch('/api/workTypes', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                workTypes = await response.json();
                populateWorkTypeSelect();
            }
        } catch (error) {
            console.error('Error loading work types:', error);
        }
    }

    // Загрузка позиций проекта
    async function loadProjectItems() {
        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch(`/api/projects/${projectId}/items`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                projectItems = await response.json();
                displayProjectItems();
                updateCalculations();
            } else {
                throw new Error('Не удалось загрузить позиции проекта');
            }
        } catch (error) {
            console.error('Error loading project items:', error);
            showError('Ошибка при загрузке позиций работ');
        }
    }

    // Заполнение выбора типов работ
    function populateWorkTypeSelect() {
        const select = document.getElementById('workTypeSelect');
        if (!select) return;

        select.innerHTML = '<option value="">Выберите тип работ...</option>';

        workTypes.forEach(workType => {
            const option = document.createElement('option');
            option.value = workType.id;
            option.textContent = `${workType.name} (${workType.unit})`;
            select.appendChild(option);
        });
    }

    // Отображение данных проекта
    function displayProjectData() {
        if (!projectData) return;

        document.getElementById('projectName').textContent = projectData.name;
        document.getElementById('projectStatusBadge').textContent = getStatusText(projectData.status);
        document.getElementById('projectStatusBadge').className = `px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap ${getStatusClass(projectData.status)}`;
        document.getElementById('projectStatusText').textContent = getStatusText(projectData.status);
        document.getElementById('projectCreated').textContent = formatDate(projectData.created_at);
        document.getElementById('projectBudget').textContent = formatCurrency(projectData.total_estimated_cost);

        document.getElementById('totalBudget').textContent = formatCurrency(projectData.total_estimated_cost);
    }

    // Отображение позиций работ
    function displayProjectItems() {
        const container = document.getElementById('workPositionsContainer');

        if (projectItems.length === 0) {
            container.innerHTML = `
                <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
                    <div class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">В смете пока нет позиций</h3>
                        <p class="text-gray-500 mb-6">Добавьте позиции работ и материалы для начала работы</p>
                        <div class="flex gap-3 justify-center">
                            <button onclick="addWorkPosition()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                + Добавить позицию
                            </button>
                            <a href="/categories?project_id=${projectId}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                                📦 Добавить материалы
                            </a>
                        </div>
                    </div>
                </div>
            `;
            return;
        }

        const itemsHTML = projectItems.map(item => `
            <div class="bg-white rounded-2xl shadow border border-gray-200 mb-6 work-position" data-item-id="${item.id}">
                <!-- Заголовок позиции -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">${escapeHtml(item.work_type_name)}</h3>
                                ${item.notes ? `<p class="text-sm text-gray-600 mt-1">${escapeHtml(item.notes)}</p>` : ''}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">${item.materials_count} материалов</span>
                            <span class="font-semibold text-green-600">${formatCurrency(item.item_total)}</span>
                            <div class="flex gap-1">
                                <button onclick="editWorkPosition(${item.id})" class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteWorkPosition(${item.id})" class="p-1 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Материалы позиции -->
                <div class="divide-y divide-gray-200">
                    ${item.materials.length > 0 ? `
                        <!-- Header таблицы -->
                        <div class="grid grid-cols-12 bg-gray-50 text-gray-600 text-xs font-medium px-6 py-3">
                            <div class="col-span-5">Наименование материала</div>
                            <div class="col-span-1 text-center">Ед.</div>
                            <div class="col-span-1 text-center">Кол-во</div>
                            <div class="col-span-2 text-right">Цена за ед.</div>
                            <div class="col-span-2 text-right">Сумма</div>
                            <div class="col-span-1 text-center"></div>
                        </div>

                        <!-- Материалы -->
                        ${item.materials.map(material => `
                            <div class="grid grid-cols-12 px-6 py-3 text-sm items-center hover:bg-gray-50 group material-item" data-material-id="${material.id}">
                                <div class="col-span-5">
                                    <div class="font-medium text-gray-900">${escapeHtml(material.name)}</div>
                                    ${material.description ? `<div class="text-xs text-gray-500 mt-1">${escapeHtml(material.description)}</div>` : ''}
                                </div>
                                <div class="col-span-1 text-center text-gray-600">${material.unit}</div>
                                <div class="col-span-1 text-center">
                                    <input type="number" value="${material.quantity}"
                                           class="quantity-input w-16 text-center border rounded-lg p-1 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                           onchange="updateMaterialQuantity(${material.id}, this.value, ${item.id})">
                                </div>
                                <div class="col-span-2 text-right text-gray-700">${formatCurrency(material.price)}</div>
                                <div class="col-span-2 text-right font-bold text-gray-900 material-total">${formatCurrency(material.total)}</div>
                                <div class="col-span-1 text-center">
                                    <button onclick="removeMaterial(${material.id}, ${item.id})" class="text-red-500 hover:text-red-700 transition-colors opacity-0 group-hover:opacity-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        `).join('')}
                    ` : `
                        <!-- Нет материалов -->
                        <div class="px-6 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="mb-4">В этой позиции пока нет материалов</p>
                            <a href="/categories?project_id=${projectId}&work_position=${item.id}" class="inline-flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Добавить материалы
                            </a>
                        </div>
                    `}
                </div>
            </div>
        `).join('');

        container.innerHTML = itemsHTML;

        // Обновляем счетчики
        document.getElementById('workPositionsCount').textContent = `${projectItems.length} позиций`;
        document.getElementById('workPositionsTotal').textContent = projectItems.length;

        // Инициализируем сортировку
        initializeSortable();
    }

    // Инициализация перетаскивания
    function initializeSortable() {
        const container = document.getElementById('workPositionsContainer');
        if (!container) return;

        new Sortable(container, {
            animation: 150,
            handle: '.work-position',
            onEnd: async function(evt) {
                const items = Array.from(container.querySelectorAll('.work-position'));
                const reorderData = items.map((item, index) => ({
                    id: parseInt(item.dataset.itemId),
                    sort_order: index
                }));

                await reorderWorkPositions(reorderData);
            }
        });
    }

    // Обновление расчетов
    function updateCalculations() {
        const materialsTotal = projectItems.reduce((sum, item) => sum + item.item_total, 0);
        const projectBudget = projectData ? (projectData.total_estimated_cost || 0) : 0;
        const savings = projectBudget - materialsTotal;

        // Новые счетчики
        const totalMaterialsCount = projectItems.reduce((sum, item) => sum + item.materials_count, 0);
        const totalWorkPositionsCount = projectItems.length;

        document.getElementById('materialsTotal').textContent = formatCurrency(materialsTotal);
        document.getElementById('materialsSubtotal').textContent = formatCurrency(materialsTotal);
        document.getElementById('grandTotal').textContent = formatCurrency(materialsTotal);
        document.getElementById('savings').textContent = formatCurrency(savings);

        // Обновляем новые счетчики
        document.getElementById('totalWorkPositionsCount').textContent = totalWorkPositionsCount;
        document.getElementById('totalMaterialsCount').textContent = totalMaterialsCount;

        // Обновляем статус бюджета
        const budgetStatus = document.getElementById('budgetStatus');
        if (savings >= 0) {
            budgetStatus.className = 'mt-4 p-3 bg-green-50 rounded-lg border border-green-200';
            budgetStatus.innerHTML = `
                <div class="flex items-center gap-2 text-sm text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">В рамках бюджета</span>
                </div>
            `;
            budgetStatus.classList.remove('hidden');
        } else {
            budgetStatus.className = 'mt-4 p-3 bg-red-50 rounded-lg border border-red-200';
            budgetStatus.innerHTML = `
                <div class="flex items-center gap-2 text-sm text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Превышение бюджета на ${formatCurrency(Math.abs(savings))}</span>
                </div>
            `;
            budgetStatus.classList.remove('hidden');
        }
    }

    // Добавление позиции работ
    function addWorkPosition() {
        document.getElementById('addWorkPositionModal').classList.remove('hidden');
    }

    function closeAddWorkPositionModal() {
        document.getElementById('addWorkPositionModal').classList.add('hidden');
        document.getElementById('addWorkPositionForm').reset();
    }

    // Обработка формы добавления позиции
    document.getElementById('addWorkPositionForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const token = localStorage.getItem('auth_token');
        const formData = {
            work_type_id: document.getElementById('workTypeSelect').value,
            notes: document.getElementById('workPositionNotes').value
        };

        if (!formData.work_type_id) {
            showError('Пожалуйста, выберите тип работ');
            return;
        }

        try {
            const response = await fetch(`/api/projects/${projectId}/items`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                closeAddWorkPositionModal();
                showSuccess('Позиция работ добавлена');
                loadProjectItems();
            } else {
                const error = await response.json();
                throw new Error(error.message || 'Ошибка при добавлении позиции');
            }
        } catch (error) {
            console.error('Error adding work position:', error);
            showError('Ошибка при добавлении позиции: ' + error.message);
        }
    });

    // Редактирование позиции работ
    function editWorkPosition(itemId) {
        const item = projectItems.find(i => i.id === itemId);
        if (!item) return;

        // Можно реализовать модальное окно редактирования
        const newNotes = prompt('Введите новое описание позиции:', item.notes || '');
        if (newNotes !== null) {
            updateWorkPosition(itemId, { notes: newNotes });
        }
    }

    // Обновление позиции работ
    async function updateWorkPosition(itemId, data) {
        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch(`/api/projects/${projectId}/items/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                showSuccess('Позиция обновлена');
                loadProjectItems();
            } else {
                throw new Error('Не удалось обновить позицию');
            }
        } catch (error) {
            console.error('Error updating work position:', error);
            showError('Ошибка при обновлении позиции');
        }
    }

    // Удаление позиции работ
    async function deleteWorkPosition(itemId) {
        if (!confirm('Удалить эту позицию работ вместе со всеми материалами?')) return;

        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch(`/api/projects/${projectId}/items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            if (response.ok) {
                showSuccess('Позиция работ удалена');
                loadProjectItems();
            } else {
                throw new Error('Не удалось удалить позицию');
            }
        } catch (error) {
            console.error('Error deleting work position:', error);
            showError('Ошибка при удалении позиции');
        }
    }

    // Изменение порядка позиций
    async function reorderWorkPositions(items) {
        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch(`/api/projects/${projectId}/items/reorder`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ items })
            });

            if (!response.ok) {
                throw new Error('Не удалось изменить порядок');
            }
        } catch (error) {
            console.error('Error reordering work positions:', error);
        }
    }

    // Обновление количества материала
    async function updateMaterialQuantity(materialId, newQuantity, itemId) {
        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch(`/api/projects/${projectId}/materials/${materialId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    quantity: parseFloat(newQuantity)
                })
            });

            if (response.ok) {
                loadProjectItems(); // Перезагружаем все данные
            } else {
                throw new Error('Не удалось обновить количество');
            }
        } catch (error) {
            console.error('Error updating material quantity:', error);
            showError('Ошибка при обновлении количества');
            loadProjectItems(); // Перезагружаем чтобы вернуть правильные значения
        }
    }

    // Удаление материала
    async function removeMaterial(materialId, itemId) {
        if (!confirm('Удалить этот материал из позиции?')) return;

        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch(`/api/projects/${projectId}/materials/${materialId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            if (response.ok) {
                showSuccess('Материал удален');
                loadProjectItems();
            } else {
                throw new Error('Не удалось удалить материал');
            }
        } catch (error) {
            console.error('Error removing material:', error);
            showError('Ошибка при удалении материала');
        }
    }

    // Редактирование проекта
    function editProject() {
        if (!projectData) return;

        document.getElementById('editName').value = projectData.name;
        document.getElementById('editDescription').value = projectData.description || '';
        document.getElementById('editStatus').value = projectData.status;
        document.getElementById('editBudget').value = projectData.total_estimated_cost || 0;

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.getElementById('editProjectForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const token = localStorage.getItem('auth_token');
        const formData = {
            name: document.getElementById('editName').value,
            description: document.getElementById('editDescription').value,
            status: document.getElementById('editStatus').value,
            total_estimated_cost: parseFloat(document.getElementById('editBudget').value) || 0
        };

        try {
            const response = await fetch(`/api/projects/${projectId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                projectData = await response.json();
                displayProjectData();
                updateCalculations();
                closeEditModal();
                showSuccess('Смета обновлена');
            } else {
                throw new Error('Не удалось обновить смету');
            }
        } catch (error) {
            console.error('Error updating project:', error);
            showError('Ошибка при обновлении сметы');
        }
    });

    // Удаление проекта
    async function deleteProject() {
        if (!confirm('Вы уверены, что хотите удалить эту смету? Это действие нельзя отменить.')) {
            return;
        }

        const token = localStorage.getItem('auth_token');

        try {
            const response = await fetch(`/api/projects/${projectId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            if (response.ok) {
                showSuccess('Смета удалена');
                setTimeout(() => {
                    window.location.href = '/project';
                }, 1000);
            } else {
                throw new Error('Не удалось удалить смету');
            }
        } catch (error) {
            console.error('Error deleting project:', error);
            showError('Ошибка при удалении сметы');
        }
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

    function showSuccess(message) {
        // Простая реализация уведомления
        alert(message);
    }

    function showError(message) {
        alert('Ошибка: ' + message);
    }

    function printEstimate() {
        window.print();
    }

    function exportToExcel() {
        alert('Функция экспорта в Excel будет реализована позже');
    }
</script>

</body>
</html>
