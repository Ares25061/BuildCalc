<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

@include('layouts.nav')

<main class="p-6">
    @yield('content')
</main>

</body>
</html>
