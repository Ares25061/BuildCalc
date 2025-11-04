<nav class="bg-gray-800 shadow-lg sticky top-0 z-50 ">
    <div class="max-w-7xl mx-auto px-">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-1">
                <span class="text-2xl">🔧</span>
                <span class="text-xl font-bold text-gray-100"><a href="/#">MaterialHub</a></span>
            </div>

            <div class="hidden md:flex space-x-8">
                <a href="/categories" class="text-gray-300 hover:text-white font-medium transition duration-200">Каталог материалов</a>
                <a href="/project" class="text-gray-300 hover:text-white font-medium transition duration-200">Сметы</a>
                <a href="#" class="text-gray-300 hover:text-white font-medium transition duration-200">Калькулятор</a>
            </div>

            <div class="flex items-center">
                <div id="guestButtons" class="flex items-center space-x-8">
                    <a href="/login" class="text-gray-300 hover:text-white transition duration-200">Вход</a>
                    <a href="/register" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition duration-200 font-medium">Регистрация</a>
                </div>

                <div id="userButtons" class="hidden">
                    <button onclick="logout()" class="flex items-center gap-2 bg-orange-50 text-orange-800 px-6 py-2 rounded-lg hover:bg-orange-200 transition duration-200 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>


<script>
    // check auth status
    window.addEventListener('load', function() {
        const token = localStorage.getItem('auth_token');
        const guestButtons = document.getElementById('guestButtons');
        const userButtons = document.getElementById('userButtons');

        if (token) {
            // logged in  - show logout button
            guestButtons.classList.add('hidden');
            userButtons.classList.remove('hidden');
        } else {
            // not logged in - show login/register buttons
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
                // clear storage
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
