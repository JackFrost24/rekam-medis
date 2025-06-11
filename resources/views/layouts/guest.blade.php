<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - Rekam Medis Klinik</title>
    
    <!-- Pastikan Vite/Tailwind terload dengan benar -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Inter untuk tampilan yang lebih modern -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    
    <!-- Styling tambahan untuk konsistensi -->
    <style>
        html {
            height: 100%;
        }
        body {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md space-y-6">
            @yield('content')
        </div>
    </div>
</body>
</html>