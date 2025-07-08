# Sistema Unificado de Direcciones en Órdenes

## Resumen del Sistema

Este documento describe el sistema unificado de gestión de direcciones de envío en las órdenes del e-commerce. El sistema centraliza la lógica de creación de órdenes y asegura que la información de direcciones se preserve correctamente en el campo JSON `shipping_address` de la tabla `orders`.

## Estructura de Datos

### Tabla `addresses`
- Almacena las direcciones de los usuarios
- Incluye información geográfica (provincia, cantón, parroquia)
- Campo `receiver_info` (JSON) con datos del receptor
- Campo `default` para marcar la dirección principal

### Tabla `orders`
- Campo `shipping_address` (JSON) que almacena toda la información de la dirección al momento de crear la orden
- Preserva los datos históricos aunque la dirección original se modifique o elimine

## Flujo de Datos

### 1. Selección de Dirección
```php
// En CheckoutController->index()
$defaultAddress = Address::where('user_id', auth()->id())
    ->where('default', true)
    ->with(['province', 'canton', 'parish'])
    ->first();
```

### 2. Creación de Orden Centralizada
```php
// En CheckoutController->createOrderFromCart()
$shipping_address = [
    // Información geográfica
    'address' => $address->address,
    'reference' => $address->reference,
    'province' => $address->province->name ?? '',
    'canton' => $address->canton->name ?? '',
    'parish' => $address->parish->name ?? '',
    'postal_code' => $address->postal_code,
    'notes' => $address->notes,
    
    // Información del receptor (usando accessors)
    'receiver_type' => $address->receiver,
    'receiver_name' => $address->receiver_name,
    'receiver_last_name' => $address->receiver_last_name,
    'receiver_full_name' => $address->receiver_full_name,
    'receiver_phone' => $address->receiver_phone,
    'receiver_email' => $address->receiver_email,
    'receiver_document_type' => $address->receiver_document_type,
    'receiver_document_number' => $address->receiver_document_number,
];
```

### 3. Guardado en la Orden
```php
Order::create([
    'user_id' => auth()->id(),
    'status' => 1,
    'payment_method' => $paymentMethod,
    'subtotal' => $subtotal,
    'shipping_cost' => $shipping,
    'total' => $totalWithShipping,
    'content' => $cartContent,
    'shipping_address' => $shipping_address, // Campo JSON
]);
```

## Accessors en el Modelo Address

El modelo `Address` incluye accessors para acceder a los datos del receptor almacenados en el campo JSON `receiver_info`:

```php
// Accessors disponibles
$address->receiver_name         // Nombre del receptor
$address->receiver_last_name    // Apellido del receptor
$address->receiver_full_name    // Nombre completo del receptor
$address->receiver_phone        // Teléfono del receptor
$address->receiver_email        // Email del receptor
$address->receiver_document_type // Tipo de documento del receptor
$address->receiver_document_number // Número de documento del receptor
```

## Métodos de Pago Soportados

### 1. Pago en Efectivo
- **Endpoint**: `POST /checkout/store`
- **Parámetro**: `payment_method=3`
- **Flujo**: Crea la orden directamente sin archivos adicionales

### 2. Transferencia Bancaria
- **Endpoint**: `POST /checkout/transfer-payment`
- **Requiere**: Archivo de comprobante (`receipt_file`)
- **Flujo**: Crea orden + registro de pago + guarda archivo

### 3. Pago QR
- **Endpoint**: `POST /checkout/qr-payment`
- **Requiere**: Archivo de comprobante (`receipt_file`)
- **Opcional**: Número de transacción (`transaction_number`)
- **Flujo**: Crea orden + registro de pago + guarda archivo

## Estructura del Campo shipping_address

```json
{
    "address": "Calle Principal 123",
    "reference": "Frente al parque central",
    "province": "Pichincha",
    "canton": "Quito",
    "parish": "Centro Histórico",
    "postal_code": "170101",
    "notes": "Entregar en horario de oficina",
    "receiver_type": "me|other",
    "receiver_name": "Juan",
    "receiver_last_name": "Pérez",
    "receiver_full_name": "Juan Pérez",
    "receiver_phone": "0987654321",
    "receiver_email": "juan@example.com",
    "receiver_document_type": "CI",
    "receiver_document_number": "1234567890"
}
```

## Vistas Actualizadas

### 1. Factura de Orden (`orders.invoice`)
- Muestra información completa del receptor y dirección
- Compatible con ambos formatos (nuevo y legacy)
- Incluye datos de contacto y ubicación geográfica

### 2. Vista de Tracking (`orders.tracking.show`)
- Presenta dirección de entrega formateada
- Fallback para formatos antiguos
- Incluye referencia y datos de contacto

### 3. Panel de Admin (`admin.orders.partials.verified-orders-content`)
- Muestra ubicación resumida (cantón, provincia)
- Compatible con estructura nueva y legacy

## Compatibilidad con Datos Legacy

El sistema mantiene compatibilidad con estructuras antiguas de `shipping_address`:

```php
// Nuevo formato (prioridad)
$order->shipping_address['receiver_full_name']

// Formato legacy (fallback)
$order->shipping_address['recipient_name']
$order->shipping_address['name']

// Ubicación nueva
$order->shipping_address['province']
$order->shipping_address['canton'] 

// Ubicación legacy
$order->shipping_address['state']
$order->shipping_address['city']
```

## Rutas del Sistema

```php
// Checkout principal
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Creación de órdenes (centralizada)
Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');

// Métodos de pago específicos
Route::post('/checkout/transfer-payment', [CheckoutController::class, 'storeTransferPayment'])->name('checkout.transfer-payment');
Route::post('/checkout/qr-payment', [CheckoutController::class, 'storeQrPayment'])->name('checkout.qr-payment');

// Páginas de confirmación
Route::get('/checkout/thank-you', [CheckoutController::class, 'thankYou'])->name('checkout.thank-you');
Route::get('/orders/{order}/invoice', [CheckoutController::class, 'downloadInvoice'])->name('orders.invoice');
```

## JavaScript del Frontend

```javascript
// Pago en efectivo
document.getElementById('cash-payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('/checkout/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: 'payment_method=3'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect_url;
        } else {
            alert(data.message);
        }
    });
});
```

## Validaciones

### Requisitos para Crear Orden
1. **Usuario autenticado**: `auth()->check()`
2. **Carrito no vacío**: `Cart::count() > 0`
3. **Dirección por defecto**: Usuario debe tener una dirección marcada como `default = true`
4. **Método de pago válido**: Solo se permiten métodos 2, 3, 4

### Validaciones de Archivos (Transferencia/QR)
- **Tipos permitidos**: jpeg, png, jpg, gif, pdf
- **Tamaño máximo**: 5MB (5120 KB)
- **Almacenamiento**: `storage/app/public/payment_receipts/`

## Logs y Depuración

El sistema incluye logging detallado para errores:

```php
\Log::error('Error al crear la orden:', [
    'error' => $e->getMessage(),
    'user_id' => auth()->id()
]);
```

## Consideraciones de Seguridad

1. **Verificación de propiedad**: Solo el usuario propietario puede acceder a sus órdenes
2. **Validación de archivos**: Tipos MIME y tamaño verificados
3. **CSRF Protection**: Todos los formularios incluyen token CSRF
4. **Sanitización de datos**: Datos del receptor validados y escapados

## Migración de Datos Legacy

Para migrar órdenes antiguas a la nueva estructura:

```php
// Script de migración (opcional)
$orders = Order::whereNotNull('shipping_address')->get();

foreach ($orders as $order) {
    $oldAddress = $order->shipping_address;
    
    // Si ya tiene la estructura nueva, continuar
    if (isset($oldAddress['receiver_full_name'])) continue;
    
    // Convertir a nueva estructura
    $newAddress = [
        'address' => $oldAddress['street'] ?? $oldAddress['address'] ?? '',
        'province' => $oldAddress['state'] ?? '',
        'canton' => $oldAddress['city'] ?? '',
        'receiver_full_name' => $oldAddress['name'] ?? $oldAddress['recipient_name'] ?? '',
        'receiver_phone' => $oldAddress['phone'] ?? '',
        // ... mapear otros campos
    ];
    
    $order->update(['shipping_address' => $newAddress]);
}
```

## Conclusión

El sistema unificado de direcciones en órdenes:

✅ **Centraliza** la lógica de creación de órdenes
✅ **Preserva** la información histórica de direcciones
✅ **Unifica** los métodos de pago
✅ **Mantiene** compatibilidad con datos legacy
✅ **Mejora** la consistencia de datos
✅ **Facilita** el mantenimiento del código

Este sistema asegura que toda la información relevante de envío se preserve adecuadamente en cada orden, independientemente del método de pago utilizado.
