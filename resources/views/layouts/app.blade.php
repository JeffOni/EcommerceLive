<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ecommerce') }}</title>

    {{-- add new stac --}}
    @stack('css')
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome --}}
    <script src="https://kit.fontawesome.com/b269b2e8c1.js" crossorigin="anonymous"></script>

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
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

    {{-- new stack in welcome view --}}
    @stack('js')

    {{-- script para mostrar el mensaje de alerta cuando se usa redirect route --}}
    @if (session('swal'))
        <script>
            //json enconde sirve par mejorar la seguridad de la aplicacion
            Swal.fire({!! json_encode(session('swal')) !!}); //json_enconde sirve para convertir un array en un objeto tipo json
        </script>
    @endif

    {{-- script para mostrar el mensaje de alerta cuando se usa emits o eventos de livewire debe estar por debajo de la importacion de srcipt de livewire --}}

    <script>
        Livewire.on('swal', (message) => { //tambien en lugar de message puede ser cualquier nombre
            Swal.fire(message[0]); //message[0] es el mensaje es importante que el mensaje este en la posicion 0
        });
    </script>

</body>

</html>
