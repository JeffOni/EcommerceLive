<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <section class="overflow-hidden bg-white rounded-lg shadow">
        <header class="px-4 py-2 bg-gray-900">
            <h2 class="text-lg font-semibold text-white">Direcciones de Envío</h2>
            <p class="mt-1 text-sm text-gray-100">Administra tus direcciones de envío.</p>
        </header>

        <div class="p-4">
            @if ($addresses->count())
            @else
                <div class="text-center">
                    <p class="text-gray-500">No tienes direcciones de envío registradas.</p>
                </div>
            @endif
        </div>

    </section>
</div>
