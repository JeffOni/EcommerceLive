@php
    $links = [
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            'name' => 'Opciones',
            'icon' => 'fa-solid fa-cog',
            'route' => route('admin.options.index'),
            'active' => request()->routeIs('admin.options.*'),
        ],
        [
            //Familias de productos
            'name' => 'Familias',
            'icon' => 'fa-solid fa-box-open',
            'route' => route('admin.families.index'),
            'active' => request()->routeIs('admin.families.*'),
        ],
        [
            //Categorias de productos
            'name' => 'Categorias',
            'icon' => 'fa-solid fa-tags',
            'route' => route('admin.categories.index'),
            'active' => request()->routeIs('admin.categories.*'),
        ],
        [
            //Subcategorias de productos
            'name' => 'Subcategorias',
            'icon' => 'fa-solid fa-tag',
            'route' => route('admin.subcategories.index'),
            'active' => request()->routeIs('admin.subcategories.*'),
        ],
        [
            'name' => 'Productos',
            'icon' => 'fa-solid fa-box',
            'route' => route('admin.products.index'),
            'active' => request()->routeIs('admin.products.*'),
        ],
        [
            'name' => 'Portadas',
            'icon' => 'fa-solid fa-image',
            'route' => route('admin.covers.index'),
            'active' => request()->routeIs('admin.covers.*'),
        ],
        // [
        //     'name' => 'Configuración',
        //     'icon' => 'fa-solid fa-gear',
        //     'route' => route('admin.settings.index'),
        //     'active' => request()->routeIs('admin.settings.*'),
        // ],
        // [
        //     'name' => 'Usuarios',
        //     'icon' => 'fa-solid fa-users',
        //     'route' => route('admin.users.index'),
        //     'active' => request()->routeIs('admin.users.*'),
        // ],
        // [
        //     'name' => 'Roles',
        //     'icon' => 'fa-solid fa-user-tag',
        //     'route' => route('admin.roles.index'),
        //     'active' => request()->routeIs('admin.roles.*'),
        // ],
        // [
        //     'name' => 'Permisos',
        //     'icon' => 'fa-solid fa-key',
        //     'route' => route('admin.permissions.index'),
        //     'active' => request()->routeIs('admin.permissions.*'),
        // ],
    ];
@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-[100dvh] pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    :class="{
        'translate-x-0 ease-out': sidebarOpen,
        '-translate-x-full ease-in': !sidebarOpen
    }"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['route'] }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                        <span class="inline-flex items-center justify-center w-6 h-6">
                            <i class="{{ $link['icon'] }} text-gray-500"></i>
                        </span>
                        <span class="ms-2">{{ $link['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>
