<!DOCTYPE html>
<html lang="en" data-theme="loansystem">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Credence system' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        input[type=range] { -webkit-appearance:none; appearance:none; height:4px; border-radius:99px; outline:none; cursor:pointer; }
        input[type=range]::-webkit-slider-thumb { -webkit-appearance:none; appearance:none; width:18px; height:18px; border-radius:50%; background:currentColor; cursor:pointer; transition:transform .1s; }
        input[type=range]::-webkit-slider-thumb:active { transform:scale(1.2); }
    </style>
</head>
<body class="min-h-screen bg-white font-sans antialiased">
    <x-mary-toast />
    {{ $slot }}
</body>
</html>
