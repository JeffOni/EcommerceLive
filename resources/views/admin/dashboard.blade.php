<x-admin-layout  :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
]">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Card Profile --}}
        <div class="p-6 bg-white shadow-lg rounden-lg ">
            <div class="flex items-center">
                <img class="object-cover rounded-full size-8" src="{{ Auth::user()->profile_photo_url }}"
                    alt="{{ Auth::user()->name }}" />
                <div class="flex-1 ms-4">
                    <h2 class="text-lg font-semibold">
                        Bienvenido de Nuevo, {{ Auth::user()->name }}
                    </h2>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                            Cerrar Sesión
                        </button>

                    </form>
                </div>
            </div>
        </div>
        {{-- Card Company --}}
        <div class="flex items-center justify-center p-6 bg-white shadow-lg rounden-lg">
            <h2 class="text-xl font-semibold">
                Pescaderia el Pescador
            </h2>
        </div>
    </div>
</x-admin-layout>
