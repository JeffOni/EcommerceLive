# 🛒 EcommerceLive - Guía de Instalación

Esta es una guía completa para configurar el proyecto EcommerceLive en tu entorno local.

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **XAMPP** (Apache, MySQL, PHP 8.1+)
- **Composer** (Gestión de dependencias PHP)
- **Node.js** y **npm** (Para assets frontend)
- **Git** (Control de versiones)

## 🚀 Instalación Paso a Paso

### 1. Clonar el Repositorio

```bash
# Navegar al directorio de XAMPP
cd C:\xampp\htdocs

# Clonar el repositorio
git clone https://github.com/JeffOni/EcommerceLive.git

# Entrar al directorio del proyecto
cd EcommerceLive
```

### 2. Configurar Variables de Entorno

```bash
# Copiar el archivo de ejemplo
copy .env.example .env
```

**Editar el archivo `.env` con estos valores:**

```env
APP_NAME=EcommerceLive
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://ecommercelive.test

APP_LOCALE=es
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=es_ES

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommercelive
DB_USERNAME=root
DB_PASSWORD=

# Configuración de sesión
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Configuración de cache y colas
CACHE_STORE=database
QUEUE_CONNECTION=database

# Configuración de correo (desarrollo)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@ecommercelive.test"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Instalar Dependencias

```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js
npm install
```

### 4. Configurar Base de Datos

#### Paso A: Crear la base de datos
1. Abrir **XAMPP Control Panel**
2. Iniciar **Apache** y **MySQL**
3. Ir a **phpMyAdmin** (http://localhost/phpmyadmin)
4. Crear nueva base de datos llamada `ecommercelive`

#### Paso B: Configurar PHP para extensión GD
1. Abrir el archivo `C:\xampp\php\php.ini`
2. Buscar la línea `;extension=gd`
3. Remover el `;` para que quede: `extension=gd`
4. Guardar y reiniciar Apache desde XAMPP

### 5. Generar Clave de Aplicación

```bash
php artisan key:generate
```

### 6. Ejecutar Migraciones y Seeders

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (datos de prueba)
php artisan db:seed
```

### 7. Crear Enlace Simbólico para Storage

```bash
php artisan storage:link
```

### 8. Compilar Assets Frontend

```bash
# Para desarrollo
npm run dev

# O para producción
npm run build
```

## 🎯 Iniciar el Proyecto

### Opción 1: Servidor integrado de Laravel
```bash
php artisan serve
```
Visita: http://localhost:8000

### Opción 2: Con XAMPP
1. Asegúrate de que Apache esté corriendo
2. Visita: http://localhost/EcommerceLive/public

## 👤 Usuarios de Prueba

Después de ejecutar los seeders, tendrás estos usuarios:

- **Admin**: Admin@example.com / password
- **Usuario**: test@example.com / password

## 🛠️ Comandos Útiles

```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Regenerar autoload
composer dump-autoload

# Ver rutas
php artisan route:list

# Ver eventos registrados
php artisan event:list
```

## 🔧 Funcionalidades Incluidas

- ✅ **Autenticación** (Laravel Jetstream)
- ✅ **Carrito de Compras** (Shopping Cart Package)
- ✅ **Catálogo de Productos** (Familias, Categorías, Subcategorías)
- ✅ **Filtros Avanzados** (Livewire)
- ✅ **Panel Administrativo**
- ✅ **Responsive Design** (Tailwind CSS)
- ✅ **Componentes Livewire**

## 📁 Estructura del Proyecto

```
app/
├── Livewire/           # Componentes Livewire
│   ├── Products/       # Gestión de productos
│   ├── Admin/          # Panel administrativo
│   └── Forms/          # Formularios
├── Models/             # Modelos Eloquent
├── Listeners/          # Event Listeners
└── Observers/          # Model Observers

resources/
├── views/              # Vistas Blade
├── css/                # Estilos CSS
└── js/                 # JavaScript

database/
├── migrations/         # Migraciones
├── seeders/           # Datos de prueba
└── factories/         # Factories para testing
```

## 🐛 Solución de Problemas Comunes

### Error: "Class 'GD' not found"
- Asegúrate de activar la extensión GD en `php.ini` (ver paso 4B)

### Error de conexión a base de datos
- Verifica que MySQL esté corriendo en XAMPP
- Confirma que la base de datos `ecommercelive` existe
- Revisa las credenciales en `.env`

### Error: "APP_KEY not set"
- Ejecuta: `php artisan key:generate`

### Assets no se cargan
- Ejecuta: `npm run dev` o `npm run build`
- Verifica que el enlace simbólico esté creado: `php artisan storage:link`

## 📞 Soporte

Si tienes algún problema durante la instalación:

1. Revisa los logs en `storage/logs/laravel.log`
2. Verifica que todos los requisitos estén instalados
3. Consulta la documentación de Laravel 11

## 🚀 ¡Listo para Desarrollar!

Una vez completados todos los pasos, tendrás el proyecto EcommerceLive funcionando completamente en tu entorno local.

---

**Desarrollado con ❤️ usando Laravel 11 + Livewire + Tailwind CSS**
