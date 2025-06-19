<x-app-layout>
    <!-- Contenedor principal que alberga el contenido del formulario de autenticación. -->
    <x-container>
        <div class="grid grid-cols-3 gap-4">

            <div class="col-span-2">
                @livewire('shipping-addresses')
            </div>
        </div>
        <div class="col-span-1">
            <div class="overflow-hidden bg-white rounded shadow-lg">
                <div class="flex items-center justify-between p-4 text-white bg-blue-600">
                    <p class="font-semibold">
                        Resumen de compras ({{ Cart::instance('shopping')->count() }})
                    </p>

                </div>

            </div>
        </div>
    </x-container>
</x-app-layout>
