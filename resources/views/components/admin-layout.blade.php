@props(['title' => 'Panel Admin'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- font awesome --}}
    <script src="https://kit.fontawesome.com/b269b2e8c1.js" crossorigin="anonymous"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    @stack('css')
</head>

<body class="font-sans antialiased" x-data="{ sidebarOpen: false }" :class="{ 'overflow-hidden': sidebarOpen }">

    {{-- x-cloak es igual que style:display:none --}}
    <div class="fixed inset-0 z-20 bg-gray-900/50 sm:hidden" x-cloak x-show="sidebarOpen"
        x-on:click="sidebarOpen = false">
    </div>

    @include('layouts.partials.admin.navigation')
    @include('layouts.partials.admin.sidebar')

    <div class="p-4 sm:ml-64">
        <div class="mt-14">
            <div class="flex items-center justify-between">
                @include('layouts.partials.admin.breadcrumb')
                {{-- slot que contiene el action para mostrar o ejectuar --}}
                @isset($action)
                <div>
                    {{ $action }}
                </div>
                @endisset
            </div>
            {{-- slot que contiene el main --}}
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 ">
                {{ $slot }}
            </div>
        </div>
    </div>

    @stack('modals')

    @livewireScripts
    @stack('js')
</body>

</html>