<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    {{-- Заголовок сайта --}}
    <title>@stack('subtitle') – TrustSpace</title>

    {{-- Настройки страницы --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Помогаем бизнесу, решая проблемы безопасности">

    {{-- Подключение шрифтов --}}
    @stack('fonts')

    {{-- Подключение стилей --}}
    <link rel="stylesheet" href="{{ asset('assets/styles/app.css') }}">
    @stack('styles')
</head>
<body>

    {{-- Содержимое страницы --}}
    @yield('content')

    {{-- Модальные окна --}}
    @stack('modals')

    {{-- Подключение скриптов --}}
    <script src="{{ asset('assets/scripts/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
