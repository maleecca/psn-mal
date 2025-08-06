<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicon')}}/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon')}}/favicon.png"> 
    <link rel="manifest" href="{{asset('favicon')}}/site.webmanifest">
    <title>LaporinAja</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet"/>

    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    @livewireStyles
    <script src="{{ asset('assets/js/app.js') }}?key={{uniqid()}}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-200">
    <div x-data="mainState" :class="{ dark: isDarkMode }" @resize.window="handleWindowResize" x-cloak>
        <x-banner/>
        <div class="min-h-screen text-gray-900 bg-gray-100 dark:bg-dark-eval-0 dark:text-gray-200 flex flex-col"
             style="background-image: url('{{asset('bg-2.png')}}'); background-size: cover; background-position: center;">
            
            <x-sidebar.sidebar/>

            <div class="flex flex-col flex-grow transition-margin duration-150" :class="{
                'lg:ml-64': isSidebarOpen,
                'md:ml-16': !isSidebarOpen
            }">
                @livewire('navigation-menu')
                <x-mobile-bottom-nav/>

                <div class="flex-grow">
                    @php($header = ucwords($header ?? \App\Helpers\Menu::getMenuLabel(request()->route()->getName())))
                    @if($header)
                        <header class="px-4 mx-auto max-w-7xl w-full sm:px-6 lg:px-8 py-4">
                            <div class="grid grid-cols-1 gap-3 bg-white py-2 px-3 shadow rounded-lg dark:bg-dark-eval-1">
                                <div class="font-semibold leading-tight flex flex-wrap gap-2">
                                    <h2 class="text-xl flex-grow ">
                                        {!! $header !!}
                                    </h2>
                                </div>
                            </div>
                        </header>
                    @endif

                    <main class="flex-1 p-4 mx-auto max-w-7xl w-full sm:p-6 lg:p-8">
                        {{ $slot }}
                    </main>
                </div>

               
            </div>
        </div>
    </div>

    @livewire('livewire-toast') 
    @stack('modals')
</body>
@livewireScripts
</html>