<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Configuraciones del Sistema',
    ],
]">

    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-blue-200/30 to-purple-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-200/30 to-blue-300/20 blur-3xl">
            </div>
        </div>

        <div class="relative">
            <div class="mx-4 my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-green-600 to-blue-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 glass-effect rounded-xl">
                                <i class="text-xl text-white fas fa-cogs"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Configuraciones del Sistema</h2>
                                <p class="text-sm text-green-100">Gestiona las configuraciones de entrega y retiro</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-8 bg-white">
                    @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Configuración de Retiro en Tienda -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-store text-blue-600 mr-2"></i>
                                Configuración de Retiro en Tienda
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la
                                        Tienda</label>
                                    <input type="text" name="pickup_address[name]"
                                        value="{{ $settings['pickup_address']->parsed_value['name'] ?? 'Tienda Principal' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                                    <input type="text" name="pickup_address[city]"
                                        value="{{ $settings['pickup_address']->parsed_value['city'] ?? 'Quito' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección
                                        Completa</label>
                                    <input type="text" name="pickup_address[address]"
                                        value="{{ $settings['pickup_address']->parsed_value['address'] ?? 'Av. Principal #123, Sector Centro' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                    <input type="text" name="pickup_address[phone]"
                                        value="{{ $settings['pickup_address']->parsed_value['phone'] ?? '+593 99 999 9999' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Horarios de
                                        Atención</label>
                                    <input type="text" name="pickup_address[hours]"
                                        value="{{ $settings['pickup_address']->parsed_value['hours'] ?? 'Lunes a Viernes: 9:00 AM - 6:00 PM' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Configuración de Entrega -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-truck text-green-600 mr-2"></i>
                                Configuración de Entrega a Domicilio
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Costo de Envío
                                        ($)</label>
                                    <input type="number" step="0.01" name="delivery_fee"
                                        value="{{ $settings['delivery_fee']->parsed_value ?? 3.00 }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Monto Mínimo para
                                        Entrega ($)</label>
                                    <input type="number" step="0.01" name="min_order_delivery"
                                        value="{{ $settings['min_order_delivery']->parsed_value ?? 25.00 }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Envío Gratis Desde
                                        ($)</label>
                                    <input type="number" step="0.01" name="free_delivery_threshold"
                                        value="{{ $settings['free_delivery_threshold']->parsed_value ?? 50.00 }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Opciones de Habilitación -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-toggle-on text-purple-600 mr-2"></i>
                                Opciones de Servicio
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" name="delivery_enabled" value="1" {{
                                        $settings['delivery_enabled']->parsed_value ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm text-gray-900">
                                        Habilitar entrega a domicilio
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="pickup_enabled" value="1" {{
                                        $settings['pickup_enabled']->parsed_value ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm text-gray-900">
                                        Habilitar retiro en tienda
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <button type="button" onclick="window.location.reload()"
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-undo mr-2"></i>Cancelar
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('css')
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
    @endpush

</x-admin-layout>