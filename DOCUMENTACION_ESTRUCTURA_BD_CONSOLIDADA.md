# Estructura de Base de Datos Consolidada

## Tablas Principales del Sistema de Órdenes

### 1. **Tabla `orders`** - Información principal de las órdenes
```php
- id (PK)
- user_id (FK users)
- order_number (unique)
- pdf_path (nullable) - Ruta del PDF generado
- content (JSON) - Productos del carrito con cantidades, precios, opciones
- status (tinyint) - Estado numérico de la orden
- payment_method (int) - Método de pago usado
- payment_id (FK payments, nullable) - Relación opcional con pagos
- subtotal (decimal)
- shipping_cost (decimal)
- total (decimal)
- shipping_address (JSON) - Dirección completa y datos del receptor
- notes (text, nullable)
- timestamps
```

**Estados de orden (status):**
- 1 = Pendiente (pending)
- 2 = Pagado/Verificado (paid)
- 3 = Procesando (processing)
- 4 = Enviado (shipped)
- 5 = Entregado (delivered)
- 6 = Cancelado (cancelled)
- 7 = Confirmado pago efectivo (confirmed_cash_delivery)

**Métodos de pago (payment_method):**
- 1 = Payphone
- 2 = Transferencia bancaria
- 3 = Pago en efectivo
- 4 = QR Deuna

### 2. **Tabla `payments`** - Gestión de pagos y comprobantes
```php
- id (PK)
- user_id (FK users)
- order_id (FK orders, nullable) - Puede ser null si la orden se crea después
- payment_method (string) - 'bank_transfer', 'qr_deuna', etc.
- amount (decimal)
- status (enum) - 'pending', 'pending_verification', 'approved', 'rejected', 'confirmed'
- transaction_id (nullable)
- transaction_number (nullable)
- receipt_path (nullable) - Ruta del comprobante subido
- comments (text, nullable)
- cart_data (JSON, nullable) - Datos del carrito al momento del pago
- response_data (JSON, nullable) - Respuestas de APIs de pago
- verified_at (timestamp, nullable)
- verified_by (FK users, nullable) - Admin que verificó
- timestamps
```

### 3. **Tabla `addresses`** - Direcciones de usuarios (ya existente)
```php
- id (PK)
- user_id (FK users)
- type (home/work)
- address (text)
- receiver (me/other)
- receiver_info (JSON) - Datos del receptor
- default (boolean)
- province_id, canton_id, parish_id (FK)
- postal_code, reference, notes
- timestamps
```

## Ventajas de la Estructura Consolidada

### ✅ **Eliminación de Duplicaciones:**
- No más migraciones conflictivas
- Campos únicos en cada tabla
- Relaciones claras y consistentes

### ✅ **Flexibilidad:**
- El campo `content` JSON permite guardar cualquier producto del carrito
- `shipping_address` JSON preserva toda la información histórica
- Relación opcional entre orders y payments

### ✅ **Performance:**
- Índices optimizados
- Menos JOINs necesarios
- Consultas más rápidas

### ✅ **Mantenimiento:**
- Estructura simple y clara
- Menos tablas que mantener
- Código más limpio

## Flujo de Datos

### Creación de Orden:
1. Usuario confirma checkout
2. Se crea record en `orders` con toda la info
3. Si hay comprobante, se crea record en `payments`
4. Se actualiza `orders.payment_id` con la relación

### Verificación de Pago:
1. Admin revisa comprobante en `payments`
2. Actualiza `payments.status` a 'approved'
3. Actualiza `orders.status` a 2 (paid)
4. Se genera PDF automáticamente

## Migraciones Eliminadas

Se eliminaron las siguientes migraciones duplicadas/innecesarias:
- `create_order_items_table.php` → Reemplazado por JSON content
- `update_orders_table_add_missing_columns.php` → Campos ya incluidos
- `add_order_id_to_payments_table.php` → Ya incluido en create_payments
- `update_orders_status_to_integer.php` → Ya es integer en create_orders
- `update_payments_table_remove_order_dependency.php` → Conflictiva
- `add_verification_fields_to_payments_table.php` → Ya incluidos
- `update_payments_table_add_confirmed_status.php` → Ya incluido

## Comando para Aplicar Cambios

```bash
# Si es desarrollo (borra toda la data):
php artisan migrate:fresh

# Si es producción:
php artisan migrate:fresh --force
```

**Nota:** Esta estructura consolidada evita conflictos, duplicaciones y simplifica el mantenimiento del sistema.
