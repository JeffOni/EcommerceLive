# Sistema de Pagos Integrado - Documentación

## Resumen del Sistema

Este sistema de pagos integrado para Laravel incluye **4 métodos de pago** para Ecuador:

1. **PayPhone** - Tarjetas de crédito/débito
2. **Transferencia Bancaria** - Con subida de comprobantes
3. **Pago en Efectivo** - Contra entrega
4. **QR De Una** - Banco Pichincha con comprobantes

## Archivos Creados/Modificados

### Backend (Laravel)

#### 1. Controlador de Pagos
- **Archivo**: `app/Http/Controllers/PaymentController.php`
- **Métodos**:
  - `processPayPhonePayment()` - Procesa pagos con PayPhone
  - `uploadTransferReceipt()` - Sube comprobantes de transferencia
  - `confirmCashPayment()` - Confirma pago en efectivo
  - `uploadQrReceipt()` - Sube comprobantes de QR

#### 2. Modelos
- **Payment**: `app/Models/Payment.php`
- **Order**: `app/Models/Order.php`

#### 3. Migraciones
- **Orders**: `database/migrations/2025_06_29_215433_create_orders_table.php`
- **Payments**: `database/migrations/2025_06_29_215500_create_payments_table.php`

#### 4. Rutas
- **Archivo**: `routes/web.php`
- **Rutas agregadas**:
  - `POST /payments/payphone`
  - `POST /payments/transfer-receipt`
  - `POST /payments/cash-confirm`
  - `POST /payments/qr-receipt`

#### 5. Configuración
- **Archivo**: `config/services.php` - Configuración de PayPhone
- **Archivo**: `.env.example` - Variables de entorno necesarias

### Frontend (Blade + Alpine.js)

#### Vista de Checkout
- **Archivo**: `resources/views/checkout/index.blade.php`
- **Características**:
  - Selector de métodos de pago con radio buttons
  - Resumen dinámico de carrito
  - Modales específicos para cada método
  - JavaScript integrado con Alpine.js

## Configuración de PayPhone

### Variables de Entorno Requeridas

Agregar al archivo `.env`:

```env
PAYPHONE_APP_ID=tu_app_id_de_payphone
PAYPHONE_APP_SECRET=tu_app_secret_de_payphone
PAYPHONE_BASE_URL=https://pay.payphoneapp.com/api
```

### Proceso de Registro en PayPhone

1. Registrarse en [PayPhone Ecuador](https://www.payphone.app/)
2. Completar proceso de verificación de comercio
3. Obtener credenciales APP_ID y APP_SECRET
4. Configurar las variables de entorno

## Estructura de Base de Datos

### Tabla Orders
```sql
- id (bigint, primary key)
- user_id (foreign key to users)
- order_number (string, unique)
- status (enum: pending, paid, pending_payment_verification, etc.)
- subtotal (decimal 10,2)
- shipping_cost (decimal 10,2)
- total (decimal 10,2)
- shipping_address (json)
- notes (text, nullable)
- timestamps
```

### Tabla Payments
```sql
- id (bigint, primary key)
- order_id (foreign key to orders)
- payment_method (enum: payphone, bank_transfer, cash_on_delivery, qr_deuna)
- amount (decimal 10,2, nullable)
- status (enum: pending, pending_verification, approved, rejected, pending_delivery)
- transaction_id (string, nullable)
- transaction_number (string, nullable)
- receipt_path (string, nullable)
- comments (text, nullable)
- response_data (json, nullable)
- timestamps
```

## Flujo de Trabajo por Método de Pago

### 1. PayPhone (Tarjetas)
1. Usuario selecciona método y hace clic en "Pagar con PayPhone"
2. Se abre modal con formulario de tarjeta
3. Datos se envían a API de PayPhone
4. Respuesta se procesa y guarda en BD
5. Estado del pedido se actualiza según resultado

### 2. Transferencia Bancaria
1. Usuario ve datos bancarios
2. Realiza transferencia externa
3. Hace clic en "Ya Transferí"
4. Sube foto/imagen del comprobante
5. Pedido queda "pendiente de verificación"
6. Admin verifica manualmente

### 3. Pago en Efectivo
1. Usuario confirma pedido
2. Se marca como "pago contra entrega"
3. Repartidor cobra al entregar

### 4. QR De Una
1. Usuario escanea QR con app De Una
2. Realiza pago en app
3. Hace clic en "Ya Pagué"
4. Sube captura de pantalla
5. Pedido queda "pendiente de verificación"

## Estados de Pedidos

- `pending` - Recién creado
- `paid` - Pagado exitosamente (PayPhone)
- `pending_payment_verification` - Esperando verificación (Transferencia/QR)
- `confirmed_cash_delivery` - Confirmado para pago en efectivo
- `processing` - En proceso
- `shipped` - Enviado
- `delivered` - Entregado
- `cancelled` - Cancelado

## Estados de Pagos

- `pending` - Pendiente
- `pending_verification` - Esperando verificación manual
- `approved` - Aprobado
- `rejected` - Rechazado
- `pending_delivery` - Esperando entrega (efectivo)

## Implementación en Producción

### 1. Configurar PayPhone
- Obtener credenciales de producción
- Configurar variables de entorno
- Probar pagos de prueba

### 2. Configurar Almacenamiento
- Configurar disk para comprobantes en `config/filesystems.php`
- Asegurar permisos de escritura en `storage/app/public/payment_receipts`

### 3. Configurar Banco para Transferencias
- Actualizar datos bancarios reales en el código
- Configurar QR real de De Una

### 4. Panel Administrativo
- Implementar vistas para verificar pagos pendientes
- Agregar funcionalidad para aprobar/rechazar comprobantes

## Seguridad

- Validación de archivos (solo imágenes, tamaño máximo)
- Tokens CSRF en todos los formularios
- Middleware de autenticación en rutas sensibles
- Encriptación de credenciales de PayPhone

## Testing

Para probar el sistema:

1. Usar credenciales de sandbox de PayPhone
2. Subir imágenes de prueba para comprobantes
3. Verificar estados de pedidos en base de datos
4. Probar todos los flujos de pago

---

## Próximos Pasos

1. **Panel de Administración**: Crear vistas para gestionar pagos pendientes
2. **Notificaciones**: Implementar emails/SMS para confirmaciones
3. **Reportes**: Dashboard con estadísticas de pagos
4. **Webhooks**: Configurar webhooks de PayPhone para actualizaciones automáticas
5. **Múltiples QRs**: Permitir múltiples cuentas bancarias/QRs
