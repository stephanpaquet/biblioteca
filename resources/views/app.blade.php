<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>Biblioteca</title>
    <meta name="description" inertia content="Biblioteca is a modern digital library and book management platform. Discover, manage, and connect with books easily online.">
    <meta property="og:title" inertia content="Biblioteca | Modern Digital Library & Book Management">
    <meta property="og:description" inertia content="Biblioteca is a modern digital library and book management platform. Discover, manage, and connect with books easily online.">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_US">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="antialiased">
    <header class="p-4">
        <a href="{{ url('/') }}" class="text-3xl font-bold no-underline text-inherit">
            {{ config('app.name', 'Biblioteca') }}
        </a>
    </header>
    @inertia
</body>
</html>
