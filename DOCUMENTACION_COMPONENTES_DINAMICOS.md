# DOCUMENTACIÓN: COMPONENTES DINÁMICOS EN LARAVEL

## 📋 Índice

1. [¿Qué son los componentes dinámicos?](#qué-son-los-componentes-dinámicos)
2. [Cuándo usar la propiedad `key`](#cuándo-usar-la-propiedad-key)
3. [Ejemplos prácticos](#ejemplos-prácticos)
4. [Mejores prácticas](#mejores-prácticas)

---

## ¿Qué son los componentes dinámicos?

Los **componentes dinámicos** son componentes de Laravel/Livewire que cambian su contenido, comportamiento o estado basándose en variables o condiciones externas.

### Características principales

- ✅ **Reutilizables** para diferentes propósitos
- ✅ **Flexibles** según el contexto
- ✅ **Eficientes** en memoria y rendimiento
- ✅ **Mantenibles** con menos código duplicado

---

## Cuándo usar la propiedad `key`

### ❌ **NO necesitas `key` cuando:**

- Solo hay una instancia del componente
- El contenido es estático
- No hay interacciones complejas

```blade
{{-- Página simple de registro --}}
<x-authentication-card maxWidth="md">
    <form><!-- formulario estático --></form>
</x-authentication-card>
```

### ✅ **SÍ necesitas `key` cuando:**

- Múltiples instancias en la misma vista
- Contenido dinámico que cambia
- Componentes Livewire con estado
- Listas de componentes repetidos

---

## Ejemplos prácticos

### 1. **Modal de autenticación combinado (Login + Register)**

#### Problema sin `key`

```blade
{{-- ❌ Los datos se pueden mezclar entre formularios --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <h2>Iniciar Sesión</h2>
        <x-authentication-card maxWidth="sm">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <x-input name="email" placeholder="Email" />
                <x-input name="password" type="password" placeholder="Contraseña" />
                <x-button>Login</x-button>
            </form>
        </x-authentication-card>
    </div>

    <div>
        <h2>Registrarse</h2>
        <x-authentication-card maxWidth="lg">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <x-input name="name" placeholder="Nombre" />
                <x-input name="email" placeholder="Email" />
                <x-input name="password" type="password" placeholder="Contraseña" />
                <x-input name="password_confirmation" type="password" placeholder="Confirmar" />
                <x-button>Registrarse</x-button>
            </form>
        </x-authentication-card>
    </div>
</div>
```

#### Solución con `key`

```blade
{{-- ✅ Cada formulario mantiene su estado independiente --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <h2>Iniciar Sesión</h2>
        <x-authentication-card :key="'login-form'" maxWidth="sm">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <x-input name="email" placeholder="Email" />
                <x-input name="password" type="password" placeholder="Contraseña" />
                <x-button>Login</x-button>
            </form>
        </x-authentication-card>
    </div>

    <div>
        <h2>Registrarse</h2>
        <x-authentication-card :key="'register-form'" maxWidth="lg">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <x-input name="name" placeholder="Nombre" />
                <x-input name="email" placeholder="Email" />
                <x-input name="password" type="password" placeholder="Contraseña" />
                <x-input name="password_confirmation" type="password" placeholder="Confirmar" />
                <x-button>Registrarse</x-button>
            </form>
        </x-authentication-card>
    </div>
</div>
```

### 2. **Modal dinámico que cambia de contenido**

#### Controlador

```php
<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra formulario dinámico de autenticación
     */
    public function showDynamicForm(string $type): View
    {
        // Validar tipos permitidos
        $allowedTypes = ['login', 'register', 'forgot-password', 'reset-password'];
        
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Tipo de formulario no válido');
        }

        // Configuración según el tipo
        $config = $this->getFormConfig($type);

        return view('auth.dynamic-form', [
            'formType' => $type,
            'config' => $config,
        ]);
    }

    /**
     * Configuración específica para cada tipo de formulario
     */
    private function getFormConfig(string $type): array
    {
        return match($type) {
            'login' => [
                'title' => 'Iniciar Sesión',
                'maxWidth' => 'md',
                'submitText' => 'Entrar',
                'route' => 'login',
            ],
            'register' => [
                'title' => 'Crear Cuenta',
                'maxWidth' => '2xl',
                'submitText' => 'Registrarse',
                'route' => 'register',
            ],
            'forgot-password' => [
                'title' => 'Recuperar Contraseña',
                'maxWidth' => 'sm',
                'submitText' => 'Enviar Link',
                'route' => 'password.email',
            ],
            'reset-password' => [
                'title' => 'Nueva Contraseña',
                'maxWidth' => 'md',
                'submitText' => 'Cambiar Contraseña',
                'route' => 'password.update',
            ],
        };
    }
}
```

#### Vista dinámica

```blade
{{-- resources/views/auth/dynamic-form.blade.php --}}
<x-guest-layout>
    {{-- ✅ Key única basada en el tipo de formulario --}}
    <x-authentication-card :key="$formType" :maxWidth="$config['maxWidth']">
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $config['title'] }}
            </h2>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route($config['route']) }}">
            @csrf

            {{-- Contenido dinámico según el tipo --}}
            @switch($formType)
                @case('login')
                    <div class="space-y-4">
                        <div>
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" type="email" name="email" :value="old('email')" 
                                required autofocus autocomplete="username" />
                        </div>
                        <div>
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" type="password" name="password" 
                                required autocomplete="current-password" />
                        </div>
                        <div class="flex items-center">
                            <x-checkbox id="remember" name="remember" />
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </div>
                    </div>
                @break

                @case('register')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" type="text" name="name" :value="old('name')" 
                                required autofocus autocomplete="name" />
                        </div>
                        <div>
                            <x-label for="last_name" value="{{ __('Last Name') }}" />
                            <x-input id="last_name" type="text" name="last_name" :value="old('last_name')" 
                                required autocomplete="family-name" />
                        </div>
                        <div class="col-span-2">
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" type="email" name="email" :value="old('email')" 
                                required autocomplete="email" />
                        </div>
                        <div>
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" type="password" name="password" 
                                required autocomplete="new-password" />
                        </div>
                        <div>
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                            <x-input id="password_confirmation" type="password" name="password_confirmation" 
                                required autocomplete="new-password" />
                        </div>
                    </div>
                @break

                @case('forgot-password')
                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                    </div>
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" type="email" name="email" :value="old('email')" 
                            required autofocus autocomplete="email" />
                    </div>
                @break

                @case('reset-password')
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">
                    <div class="space-y-4">
                        <div>
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" type="email" name="email" :value="old('email', request()->email)" 
                                required autofocus autocomplete="email" />
                        </div>
                        <div>
                            <x-label for="password" value="{{ __('New Password') }}" />
                            <x-input id="password" type="password" name="password" 
                                required autocomplete="new-password" />
                        </div>
                        <div>
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                            <x-input id="password_confirmation" type="password" name="password_confirmation" 
                                required autocomplete="new-password" />
                        </div>
                    </div>
                @break
            @endswitch

            <div class="flex items-center justify-end mt-6">
                <x-button class="w-full">
                    {{ $config['submitText'] }}
                </x-button>
            </div>
        </form>

        {{-- Enlaces adicionales según el tipo --}}
        <div class="mt-4 text-center">
            @if($formType === 'login')
                <div class="space-y-2">
                    <a href="{{ route('dynamic-auth.show', 'forgot-password') }}" 
                       class="text-sm text-blue-600 hover:text-blue-500">
                        ¿Olvidaste tu contraseña?
                    </a>
                    <br>
                    <a href="{{ route('dynamic-auth.show', 'register') }}" 
                       class="text-sm text-blue-600 hover:text-blue-500">
                        ¿No tienes cuenta? Regístrate
                    </a>
                </div>
            @elseif($formType === 'register')
                <a href="{{ route('dynamic-auth.show', 'login') }}" 
                   class="text-sm text-blue-600 hover:text-blue-500">
                    ¿Ya tienes cuenta? Inicia sesión
                </a>
            @elseif($formType === 'forgot-password')
                <a href="{{ route('dynamic-auth.show', 'login') }}" 
                   class="text-sm text-blue-600 hover:text-blue-500">
                    Volver al inicio de sesión
                </a>
            @endif
        </div>
    </x-authentication-card>
</x-guest-layout>
```

#### Rutas

```php
<?php
// routes/web.php

use App\Http\Controllers\AuthController;

// Ruta dinámica para diferentes tipos de autenticación
Route::get('/auth/{type}', [AuthController::class, 'showDynamicForm'])
    ->name('dynamic-auth.show')
    ->where('type', 'login|register|forgot-password|reset-password');
```

### 3. **Componente Livewire con estados dinámicos**

#### Componente Livewire

```php
<?php
// app/Livewire/UserManager.php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UserManager extends Component
{
    public string $mode = 'list'; // 'list', 'create', 'edit', 'view'
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public bool $showModal = false;

    /**
     * Cambiar modo del componente
     */
    public function setMode(string $mode, ?int $userId = null): void
    {
        $this->mode = $mode;
        $this->userId = $userId;
        $this->resetForm();

        if ($mode === 'edit' && $userId) {
            $this->loadUser($userId);
        }

        $this->showModal = in_array($mode, ['create', 'edit', 'view']);
    }

    /**
     * Cargar datos del usuario
     */
    private function loadUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->name = $user->name;
        $this->email = $user->email;
    }

    /**
     * Resetear formulario
     */
    private function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
    }

    /**
     * Crear usuario
     */
    public function createUser(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ]);

        User::create($validated);
        $this->setMode('list');
        session()->flash('message', 'Usuario creado exitosamente.');
    }

    /**
     * Actualizar usuario
     */
    public function updateUser(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
        ]);

        User::findOrFail($this->userId)->update($validated);
        $this->setMode('list');
        session()->flash('message', 'Usuario actualizado exitosamente.');
    }

    public function render()
    {
        return view('livewire.user-manager', [
            'users' => User::paginate(10),
        ]);
    }
}
```

#### Vista del componente

```blade
{{-- resources/views/livewire/user-manager.blade.php --}}
<div>
    {{-- Barra de acciones --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Gestión de Usuarios</h2>
        <x-button wire:click="setMode('create')" class="bg-green-600 hover:bg-green-700">
            Crear Usuario
        </x-button>
    </div>

    {{-- Mensajes de éxito --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    {{-- Lista de usuarios --}}
    @if($mode === 'list')
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <li class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <x-button wire:click="setMode('view', {{ $user->id }})" 
                                class="bg-blue-600 hover:bg-blue-700">
                                Ver
                            </x-button>
                            <x-button wire:click="setMode('edit', {{ $user->id }})" 
                                class="bg-yellow-600 hover:bg-yellow-700">
                                Editar
                            </x-button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{ $users->links() }}
    @endif

    {{-- Modal dinámico --}}
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            {{-- ✅ Key dinámica basada en modo y usuario --}}
            <x-authentication-card :key="$mode . '-' . ($userId ?? 'new')" maxWidth="lg" class="mt-20">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">
                        @switch($mode)
                            @case('create')
                                Crear Nuevo Usuario
                            @break
                            @case('edit')
                                Editar Usuario #{{ $userId }}
                            @break
                            @case('view')
                                Ver Usuario #{{ $userId }}
                            @break
                        @endswitch
                    </h3>
                    <button wire:click="setMode('list')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Contenido dinámico del modal --}}
                @if($mode === 'create' || $mode === 'edit')
                    <form wire:submit.prevent="{{ $mode === 'create' ? 'createUser' : 'updateUser' }}">
                        <div class="space-y-4">
                            <div>
                                <x-label for="name" value="Nombre" />
                                <x-input id="name" wire:model="name" type="text" required autofocus />
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-label for="email" value="Email" />
                                <x-input id="email" wire:model="email" type="email" required />
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2 mt-6">
                            <x-button type="button" wire:click="setMode('list')" 
                                class="bg-gray-600 hover:bg-gray-700">
                                Cancelar
                            </x-button>
                            <x-button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700">
                                {{ $mode === 'create' ? 'Crear' : 'Actualizar' }}
                            </x-button>
                        </div>
                    </form>

                @elseif($mode === 'view')
                    <div class="space-y-4">
                        <div>
                            <strong>Nombre:</strong> {{ $name }}
                        </div>
                        <div>
                            <strong>Email:</strong> {{ $email }}
                        </div>
                        <div>
                            <strong>ID:</strong> {{ $userId }}
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <x-button wire:click="setMode('edit', {{ $userId }})" 
                            class="bg-yellow-600 hover:bg-yellow-700 mr-2">
                            Editar
                        </x-button>
                        <x-button wire:click="setMode('list')" 
                            class="bg-gray-600 hover:bg-gray-700">
                            Cerrar
                        </x-button>
                    </div>
                @endif
            </x-authentication-card>
        </div>
    @endif
</div>
```

### 4. **Lista de componentes repetidos**

```blade
{{-- Lista de productos con componentes individuales --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($products as $product)
        {{-- ✅ Key única para cada producto --}}
        <x-product-card :key="'product-' . $product->id" :product="$product">
            <div class="border rounded-lg p-4 shadow">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                <h3 class="font-bold mt-2">{{ $product->name }}</h3>
                <p class="text-gray-600">${{ $product->price }}</p>
                <x-button wire:click="addToCart({{ $product->id }})" class="w-full mt-2">
                    Agregar al Carrito
                </x-button>
            </div>
        </x-product-card>
    @endforeach
</div>
```

---

## Mejores prácticas

### 1. **Nomenclatura de keys**

```blade
{{-- ✅ Descriptiva y única --}}
<x-component :key="'user-edit-' . $user->id">
<x-component :key="'product-cart-' . $product->id">
<x-component :key="$formType . '-' . time()">

{{-- ❌ Genérica o repetitiva --}}
<x-component :key="'item'">
<x-component :key="$loop->index">
```

### 2. **Cuándo forzar recreación**

```blade
{{-- Recrear siempre (útil para formularios que deben estar limpios) --}}
<x-component :key="time()">

{{-- Recrear cuando cambia el contexto --}}
<x-component :key="$user->id . '-' . $mode">

{{-- Recrear cuando cambia el estado crítico --}}
<x-component :key="$formType . '-' . $step">
```

### 3. **Performance**

```blade
{{-- ✅ Key estable para mejor performance --}}
<x-component :key="'stable-' . $item->id">

{{-- ❌ Key que cambia constantemente --}}
<x-component :key="time() . rand()">
```

### 4. **Debugging**

```blade
{{-- Agregar comentarios para explicar la key --}}
{{-- Key única para evitar conflicto entre modales de crear/editar --}}
<x-authentication-card :key="$mode . '-modal'" maxWidth="lg">
    <!-- contenido -->
</x-authentication-card>
```

---

## 🎯 Resumen

### Usa `key` cuando

- ✅ Múltiples instancias del mismo componente
- ✅ Contenido dinámico que cambia
- ✅ Estados de Livewire que deben reiniciarse
- ✅ Listas de elementos repetidos

### No uses `key` cuando

- ❌ Solo hay una instancia del componente
- ❌ El contenido es completamente estático
- ❌ No hay riesgo de conflictos de estado

### Key ideal

- 📝 **Descriptiva**: `'user-edit-modal'` mejor que `'modal'`
- 🔒 **Única**: Incluye IDs o tipos específicos
- ⚡ **Estable**: No cambia innecesariamente
- 🐛 **Debuggeable**: Fácil de identificar en herramientas de desarrollo

---

*Documentación creada el: {{ date('d/m/Y H:i') }}*
*Proyecto: EcommerceLive*
*Autor: GitHub Copilot*
