<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Familias',
        'route' => route('admin.families.index'),
    ],
    [
        'name' => $family->name,
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.families.index') }}" type="secondary" name="Regresar" />
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 relative overflow-hidden">
        <!-- Decorative background elements -->
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-200/20 to-purple-200/20 rounded-full -translate-y-16 translate-x-16">
        </div>
        <div
            class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-200/20 to-cyan-200/20 rounded-full translate-y-12 -translate-x-12">
        </div>

        <!-- Header -->
        <div class="text-center mb-8 pt-8">
            <h1
                class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                Editar Familia
            </h1>
            <p class="text-gray-600 text-lg">Modifica la información de la familia "{{ $family->name }}"</p>
        </div>

        <div class="max-w-4xl mx-auto px-6 pb-12">
            <div
                class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden relative">
                <!-- Decorative gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

                <!-- Content -->
                <div class="relative p-8">
                    <form action="{{ route('admin.families.update', $family) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <x-validation-errors class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl" />

                        <!-- Form Header -->
                        <div class="flex items-center space-x-4 mb-8">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-edit text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Información de la Familia</h2>
                                <p class="text-gray-600">Actualiza los datos de la familia</p>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-200/60">
                            <div class="space-y-4">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Nombre de la Familia') }}">
                                    <i class="fas fa-sitemap mr-2 text-indigo-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    placeholder="Ingrese el nombre de la familia" name="name"
                                    value="{{ old('name', $family->name) }}" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-6 pt-6 border-t border-gray-200">
                            <button type="button" onclick="confirmDelete()"
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold transform hover:scale-105 text-lg flex items-center space-x-2">
                                <i class="fas fa-trash mr-2 text-white"></i>
                                <span class="text-white">Eliminar {{ $family->name }}</span>
                            </button>
                            <button type="submit"
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 text-lg flex items-center space-x-2">
                                <i class="fas fa-save mr-2 text-white"></i>
                                <span class="text-white">Actualizar Familia</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- formulario para eliminar  --}}
    <form action="{{ route('admin.families.destroy', $family) }}" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
    </form>

    @push('js')
        <script>
            let confirmDelete = () => {
                // Sweet Alert 2
                Swal.fire({
                    title: "¿Estás Seguro?",
                    text: "No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, Bórralo!",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form').submit();
                    }
                });
            }
        </script>
    @endpush

</x-admin-layout>
