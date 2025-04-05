@if (count($breadcrumbs))
    <nav class="mb-4">
        {{-- Enlaces de Breadcrumb --}}
        <ol class="flex flex-wrap">
            @foreach ($breadcrumbs as $item)
                <li
                    class="text-sm leading-normal text-slate-700 {{ !$loop->first ? "pl-2 before:float-left before:pr-2 before:content-['/']" : '' }}">
                    @isset($item['route'])
                        {{-- Si existe la ruta envia el dato dentro de la iteracion breadcrumbs con soudonimo item --}}
                        <a href="{{ $item['route'] }}" class="opacity-50">{{ $item['name'] }}</a>
                    @else
                        {{-- Si no existe la ruta solo muestra el nombre --}}
                        <span>{{ $item['name'] }}</span>
                    @endisset

                </li>
            @endforeach
        </ol>
        @if (count($breadcrumbs) > 1)
            <h6 class="font-bold text-slate-800">
                {{-- con el metodo end recupera el ultimo elemento del array y se accede al valor de llave en este caso name --}}
                {{ end($breadcrumbs)['name'] }}
            </h6>
        @endif
    </nav>
@endif
