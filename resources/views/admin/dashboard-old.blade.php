<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
]">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Card Profile --}}
        <div class="p-6 bg-white shadow-lg rounded-lg">
            <div class="flex items-center">
                <img class="object-cover rounded-full size-16 border-2 border-gray-200"
                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                <div class="flex-1 ms-4">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Bienvenido de Nuevo, <span class="text-primary-600">{{ Auth::user()->name }}</span>
                    </h2>
                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit"
                            class="text-xs px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
        {{-- Card Company --}}
        <div class="flex flex-col items-center justify-center p-6 bg-white shadow-lg rounded-lg">
            <img src="/img/logo.png" alt="Logo" class="w-16 h-16 mb-2">
            <h2 class="text-xl font-semibold text-gray-800">
                Pescadería el Pescador
            </h2>
            <span class="text-xs text-gray-400 mt-1">Panel de administración</span>
        </div>
    </div>
</x-admin-layout>
