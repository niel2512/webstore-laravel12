<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ isset($title) ? "$title - " . config('app.name') : config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="manifest" href="/site.webmanifest">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="dark:bg-neutral-800 dark:border-neutral-700">
    <x-navigation />
    <x-alert />
    {{ $slot }}
    <x-footer />
</body>

</html>
