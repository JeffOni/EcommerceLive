<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <section class="bg-white rounded-lg shadow-lg ">

        <header class="px-6 py-2 border-b border-gray-200">
            <div class="flex justify-between">
                <h1 class="text-lg font-semibold text-gray-700">Opciones</h1>
                <x-button wire:click="$set('newOption.openModal', true)" name="Nuevo" />
            </div>


        </header>

        <div class="p-6">

            <div class="space-y-6">

                {{--
                    wire:key="option-{{ $option->id }}"
                    Este atributo le da una "llave" única a cada tarjeta de opción.
                    Sirve para que Livewire pueda identificar cada elemento de la lista de forma individual.
                    Así, si cambias, agregas o eliminas una opción, Livewire sabe exactamente cuál actualizar en la interfaz,
                    evitando errores o confusiones al mostrar los datos.
                    Es como ponerle una etiqueta única a cada tarjeta para que Livewire no se confunda.
                --}}
                @foreach ($options as $option)
                    {{-- Tarjeta de Opcion --}}
                    <div class="relative p-6 border border-gray-200 rounded-lg" wire:key="option-{{ $option->id }}">

                        {{-- Nombre de Opciones --}}
                        <div class="absolute px-4 bg-white -top-3.5">

                            <button class="mr-1" type="button" onclick="confirmDelete({{ $option->id }}, 'option')"
                                {{-- wire:click="deleteOption({{ $option->id }})" --}}>
                                {{-- icono de eliminar --}}
                                <i class="text-red-500 fa-solid fa-trash-can hover:text-red-600"></i>

                            <span>
                                {{ $option->name }}
                            </span>

                        </div>
                        {{-- Valores --}}

                        <div class="flex flex-wrap mb-4">

                            @foreach ($option->features as $feature)
                                @switch($option->type)
                                    @case(1)
                                        <span
                                            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 pl-2.5 pr-1.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">
                                            {{ $feature->description }}

                                            <button class="ml-0.5" onclick="confirmDelete({{ $feature->id }}, 'feature')"
                                                {{-- wire:click="deleteFeature({{ $feature->id }})" --}}>
                                                {{-- icono de eliminar --}}

                                                <i class="fa-solid fa-xmark hover:text-red-500"></i>

                                            </button>
                                        </span>
                                    @break

                                    @case(2)
                                        <div class="relative">
                                            {{-- para obtener colores se obtiene el color guardado en value --}}
                                            <span
                                                class="inline-block w-6 h-6 mr-4 border-2 border-gray-300 rounded-full shadow-lg"
                                                style="background-color: {{ $feature->value }};">
                                            </span>

                                            <button
                                                class="absolute z-10 flex items-center justify-center w-4 h-4 bg-gray-500 rounded-full hover:bg-red-500 -top-2 left-3"
                                                onclick="confirmDelete({{ $feature->id }}, 'feature')"
                                                {{-- wire:click="deleteFeature({{ $feature->id }})" --}}>
                                                {{-- icono de eliminar --}}
                                                <i class="text-xs text-white fa-solid fa-xmark"></i>

                                            </button>

                                        </div>
                                    @break

                                    @default
                                @endswitch
                            @endforeach

                        </div>

                        {{-- Fin Valores --}}

                        {{-- componente livewire de feature --}}
                        <div>
                            {{--
                            key('add-new-feature-' . $option->id)
                            Aquí usamos una "key" única para el componente Livewire hijo.
                            Esto le ayuda a Livewire a distinguir cada componente "add-new-feature" según la opción a la que pertenece.
                            Así, si tienes varios componentes iguales en la página, cada uno mantiene su propio estado y datos,
                            y Livewire no mezcla la información entre ellos.
                            Es como darle un nombre único a cada formulario para que no se mezclen.
                            --}}
                            @livewire('admin.options.add-new-feature', ['option' => $option], key('add-new-feature-' . $option->id))
                        </div>

                    </div>
                    {{-- Fin Tarjeta de Opcion --}}
                @endforeach

            </div>

        </div>

    </section>

    {{-- Modal Section --}}

    <x-dialog-modal wire:model="newOption.openModal" title="Crear Nueva Opcion">
        <x-slot name="content">

            <x-validation-errors class="mb-4" />
            {{-- Nombres --}}
            <div class="grid grid-cols-2 gap-6 mb-4">

                <div>

                    <x-label class="mb-1" value="{{ __('Nombre') }}" />
                    <x-input class="w-full" wire:model.defer="newOption.name" placeholder="Tamano , color" />
                </div>

                <div>
                    <x-label class="mb-1" value="{{ __('Tipo') }}" />
                    <x-select wire:model.live="newOption.type" class="w-full">
                        <option value="" disabled>Seleccione un tipo</option>
                        {{-- 1 = Texto, 2 = Color --}}
                        <option value="1">Texto</option>
                        <option value="2">Color</option>
                    </x-select>
                </div>

            </div>
            {{-- Fin Nombres --}}

            {{-- Valores --}}
            <div class="flex items-center mb-4">

                <hr class="flex-1 border-gray-300">

                <span class="mx-4">Valores</span>

                <hr class="flex-1 border-gray-300">

            </div>

            {{-- input de valores --}}
            <div class="mb-4 space-y-4">

                @foreach ($newOption->features as $index => $feature)
                    <div class="relative p-6 border border-gray-200 rounded-lg" wire:key="feature-{{ $index }}">
                        <div class="absolute px-4 bg-white -top-3.5">
                            {{-- elimina el feature que se esta seleccionando de acuerdo a su index --}}
                            <button type="button" wire:click="removeFeature({{ $index }})">
                                <i class="text-red-500 fa-solid fa-trash-can hover:text-red-600"></i>
                            </button>

                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-4">

                            <div>
                                <x-label class="mb-1" value="{{ __('Valor') }}" />


                                @switch($newOption->type)
                                    @case(1)
                                        <x-input class="w-full" placeholder="Ingrese el valor de la opcion"
                                            wire:model.defer="newOption.features.{{ $index }}.value" />
                                    @break

                                    @case(2)
                                        {{-- para obtener colores se obtiene el color guardado en value --}}
                                        {{-- el valor ternario ?: verifica si el valor extiste y si no esta null sino manda el mensaje si el valor es null --}}

                                        <div
                                            class="border border-gray-300 h-[42px] px-3 rounded-md flex items-center justify-between">
                                            {{ $newOption->features[$index]['value'] ?: 'Seleccione Un Color' }}
                                            <x-input type="color"
                                                wire:model.live="newOption.features.{{ $index }}.value" />

                                        </div>
                                    @break

                                    @default
                                @endswitch
                            </div>

                            <div>
                                <x-label class="mb-1" value="{{ __('Descripción') }}" />
                                <x-input class="w-full" placeholder="Ingrese una descripcion"
                                    wire:model.defer="newOption.features.{{ $index }}.description" />
                            </div>

                        </div>

                    </div>
                @endforeach

                <div class="flex justify-end">
                    <x-button wire:click="addFeature" name="Agregar Valor" />
                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex justify-end gap-x-3">
                <x-danger-button wire:click="$set('newOption.openModal', false)" name="Cancelar" negative />
                <x-button wire:click="addOption" name="Guardar" positive />
            </div>
        </x-slot>
    </x-dialog-modal>

    {{-- Fin Modal Section --}}

    @push('js')
        <script>
            let confirmDelete = (id , type) => {
                // Sweet Alert 2
                Swal.fire({
                    title: "Estás Seguro?",
                    text: "No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, Bórralo!",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Swal.fire({
                        //     title: "Eliminado!",
                        //     text: "Su archivo ha sido eliminado.",
                        //     icon: "success"
                        // });
                        // @this.call('deleteFeature', featureId);
                        switch (type) {
                            case 'feature':
                                @this.call('deleteFeature', id);
                                break;
                            case 'option':
                                @this.call('deleteOption', id);
                                break;
                            default:
                                break;
                        }
                    }
                });

            }
        </script>
    @endpush

</div>
