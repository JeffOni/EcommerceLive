<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <section class="bg-white rounded-lg shadow-lg ">

        <header class="px-6 py-2 border-b border-gray-200">

            <h1 class="text-lg font-semibold text-gray-700">Opciones</h1>

        </header>

        <div class="p-6">

            <div class="space-y-6">

                @foreach ($options as $option)
                    {{-- Tarjeta de Opcion --}}
                    <div class="relative p-6 border border-gray-200 rounded-lg">
                        {{-- Nombre de Opciones --}}
                        <div class="absolute px-4 bg-white -top-3.5">

                            <span>
                                {{ $option->name }}
                            </span>

                        </div>
                        {{-- Valores --}}

                        <div class="flex flex-wrap">

                            @foreach ($option->features as $feature)
                                @switch($option->type)
                                    @case(1)
                                        <span
                                            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">
                                            {{ $feature->description }}
                                        </span>
                                    @break

                                    @case(2)
                                        {{-- para obtener colores se obtiene el color guardado en value --}}
                                        <span class="inline-block w-6 h-6 mr-4 border-2 border-gray-300 rounded-full shadow-lg"
                                            style="background-color: {{ $feature->value }};">
                                        </span>
                                    @break

                                    @default

                                @endswitch

                            @endforeach

                        </div>

                    </div>
                    {{-- Fin Tarjeta de Opcion --}}
                @endforeach

            </div>

        </div>

    </section>

</div>
