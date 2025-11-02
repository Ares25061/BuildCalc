<nav class="bg-gray-800 shadow-lg sticky top-0 z-50 ">
    <div class="max-w-7xl mx-auto px-">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-1">
                <span class="text-2xl">🔧</span>
                <span class="text-xl font-bold text-gray-100"><a href="/#">MaterialHub</a></span>
            </div>

            <div class="hidden md:flex space-x-8">
                <a href="/categories" class="text-gray-300 hover:text-white font-medium transition duration-200">Каталог материалов</a>
                <a href="#" class="text-gray-300 hover:text-white font-medium transition duration-200">Сметы</a>
                <a href="#" class="text-gray-300 hover:text-white font-medium transition duration-200">Калькулятор</a>
            </div>

            <div class="flex items-center">
                <div id="guestButtons" class="flex items-center space-x-8">
                    <a href="/login" class="text-gray-300 hover:text-white transition duration-200">Вход</a>
                    <a href="/register" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition duration-200 font-medium">Регистрация</a>
                </div>

                <div id="userButtons" class="hidden">
                    <button onclick="logout()" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition duration-200 font-medium">
                        Выйти из аккаунта
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
