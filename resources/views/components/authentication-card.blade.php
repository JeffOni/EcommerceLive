{{-- 
    COMPONENTE DE TARJETA DE AUTENTICACIÓN
    =====================================
    
    Este componente proporciona un layout consistente para todas las páginas
    de autenticación (login, registro, recuperación de contraseña, etc.)
    
    CARACTERÍSTICAS:
    - Layout centrado responsive para formularios de auth
    - Soporte para diferentes tamaños de tarjeta (sm, md, lg, xl, 2xl)
    - Diseño adaptable para móvil y desktop
    - Slots para logo personalizable y contenido del formulario
    - Tema oscuro/claro integrado
    
    PROPS DISPONIBLES:
    @param string $maxWidth - Tamaño máximo de la tarjeta (sm|md|lg|xl|2xl)
                             Por defecto: 'md'
    
    SLOTS:
    - $logo: Contenido del logo (generalmente imagen o SVG)
    - $slot: Contenido principal del formulario de autenticación
    
    @author Tu Nombre
    @version 1.0
--}}

{{-- Definir las propiedades que acepta el componente --}}
@props(['maxWidth' => null])

{{-- 
    CONFIGURACIÓN DE TAMAÑOS RESPONSIVOS
    ===================================
    
    Array que mapea los nombres de tamaño a clases de Tailwind CSS.
    Esto permite controlar el ancho máximo de la tarjeta de autenticación
    de forma responsive, aplicándose solo en pantallas sm (640px) y superiores.
--}}
@php
    $maxWidth = [
        'sm' => 'sm:max-w-sm', // ~384px en pantallas >= 640px
        'md' => 'sm:max-w-md', // ~448px en pantallas >= 640px (por defecto)
        'lg' => 'sm:max-w-lg', // ~512px en pantallas >= 640px
        'xl' => 'sm:max-w-xl', // ~576px en pantallas >= 640px
        '2xl' => 'sm:max-w-2xl', // ~672px en pantallas >= 640px
    ][$maxWidth ?? 'md']; // Si no se especifica, usar 'md' como valor por defecto
@endphp

{{-- 
    CONTENEDOR PRINCIPAL DE LA PÁGINA DE AUTENTICACIÓN
    ================================================
    
    Estructura el layout principal de la página con las siguientes características:
    - min-h-screen: Altura mínima de toda la pantalla (100vh)
    - flex flex-col: Layout flexbox en columna
    - sm:justify-center: Centra verticalmente en pantallas >= 640px
    - items-center: Centra horizontalmente siempre
    - pt-6 sm:pt-0: Padding superior adaptable (24px móvil, 0 desktop)
    - bg-gray-100 dark:bg-gray-900: Fondo adaptable al tema claro/oscuro
--}}
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">

    {{-- 
        SECCIÓN DEL LOGO
        ===============
        
        Contenedor para el logo de la aplicación que se muestra encima
        del formulario de autenticación. Se renderiza desde el slot $logo.
    --}}
    <div>
        {{ $logo }}
    </div>

    {{-- 
        TARJETA DE CONTENIDO PRINCIPAL
        =============================
        
        Contenedor principal que aloja el formulario de autenticación con:
        - w-full {{ $maxWidth }}: Ancho completo hasta el máximo configurado
        - mt-6: Margen superior de 24px (separación del logo)
        - px-6 py-4: Padding interno (24px horizontal, 16px vertical)
        - bg-white dark:bg-gray-800: Fondo blanco/gris oscuro según tema
        - shadow-md: Sombra media para efecto de elevación
        - overflow-hidden: Oculta cualquier contenido que se desborde
        - sm:rounded-lg: Bordes redondeados en pantallas >= 640px
        
        El contenido del formulario se inyecta através del slot principal ($slot)
    --}}
    <div
        class="w-full {{ $maxWidth }} mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
