<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>Biblioteca</title>
    <meta name="description" inertia content="Biblioteca is a modern digital library and book management platform. Discover, manage, and connect with books easily online.">
    <meta property="og:title" inertia content="Biblioteca | Modern Digital Library & Book Management">
    <meta property="og:description" inertia content="Biblioteca is a modern digital library and book management platform. Discover, manage, and connect with books easily online.">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_US">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @inertiaHead
    @routes
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="antialiased">
    @inertia
</body>
</html>
