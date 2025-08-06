<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicon')}}/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon')}}/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon')}}/favicon.png">
    <link rel="manifest" href="{{asset('favicon')}}/site.webmanifest">
    <title>LaporinAja</title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet"/>

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-200">
<div>
    <div class="flex flex-col min-h-screen text-gray-900 bg-gray-100 dark:bg-dark-eval-0 dark:text-gray-200"
         style="background-image: url('{{ asset('bg.png') }}'); background-size: cover; background-position: center;">
        @livewire('livewire-toast')
        {{ $slot }}
    </div>
</div>
</body>
@livewireScripts
</html>
