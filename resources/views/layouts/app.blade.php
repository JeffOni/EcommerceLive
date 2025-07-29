<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ecommerce') }}</title>

    {{-- add new stac --}}
    @stack('css')

    <!-- Overflow Control Styles -->
    <style>
        /* Control global de overflow horizontal */
        html,
        body {
            overflow-x: hidden !important;
            max-width: 100vw;
        }

        /* Asegurar que todos los contenedores respeten el ancho */
        * {
            box-sizing: border-box;
        }

        /* Control específico para breadcrumbs */
        .breadcrumb-container {
            max-width: 100%;
            overflow-x: hidden;
        }

        .breadcrumb-scroll {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        .breadcrumb-scroll::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari */
        }

        /* Responsive para dispositivos muy pequeños */
        @media (max-width: 380px) {

            body,
            html {
                overflow-x: hidden !important;
                width: 100%;
                max-width: 100vw;
            }

            .breadcrumb-item {
                flex-shrink: 0;
                max-width: fit-content;
            }
        }
    </style>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome --}}
    <script src="https://kit.fontawesome.com/b269b2e8c1.js" crossorigin="anonymous"></script>

    <!-- Styles -->
    @livewireStyles

    <!-- Favicon and App Icons -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
</head>

<body class="font-sans antialiased overflow-x-hidden">
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 overflow-x-hidden">
        {{-- @livewire('navigation-menu') --}}

        @livewire('navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow dark:bg-gray-800">
            <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        {{-- footer --}}
        <div class="mt-12">
            @include('layouts.partials.app.footer')
        </div>
    </div>

    @stack('modals')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireScripts

    {{-- Alpine.js ya está incluido en Livewire 3, no necesitamos CDN adicional --}}
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    {{-- new stack in welcome view --}}
    @stack('js')

    {{-- script para mostrar el mensaje de alerta cuando se usa redirect route --}}
    @if (session('swal'))
    <script>
        //json enconde sirve par mejorar la seguridad de la aplicacion
            Swal.fire({!! json_encode(session('swal')) !!}); //json_enconde sirve para convertir un array en un objeto tipo json
    </script>
    @endif

    {{-- script para mostrar el mensaje de alerta cuando se usa emits o eventos de livewire debe estar por debajo de la
    importacion de srcipt de livewire --}}

    <script>
        Livewire.on('swal', (message) => { //tambien en lugar de message puede ser cualquier nombre
            Swal.fire(message[0]); //message[0] es el mensaje es importante que el mensaje este en la posicion 0
        });
    </script>

</body>

</html>