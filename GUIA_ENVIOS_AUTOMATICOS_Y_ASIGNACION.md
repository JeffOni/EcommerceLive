# 📋 Guía Completa: Sistema de Envíos Automáticos y Asignación de Repartidores

## 🤖 **¿Cómo Funcionan los Envíos Automáticos?**

### Flujo Automático Paso a Paso:

1. **👤 Cliente hace un pedido** (transferencia, tarjeta o efectivo)
   
2. **🏠 Cliente ingresa su dirección** en el checkout
   
3. **🤖 El sistema verifica automáticamente**:
   - ¿La provincia es Pichincha o Manabí? ✅ → Crea envío automático
   - ¿La provincia es otra? ❌ → Solo crea la orden (retiro en tienda)

4. **📦 Si se crea el envío**:
   - Estado inicial: **PENDIENTE** 
   - Esperando asignación de repartidor
   - Visible en el panel de admin

### Ejemplo Práctico:
```
Cliente A: Vive en Quito, Pichincha → ✅ SE CREA ENVÍO AUTOMÁTICO
Cliente B: Vive en Guayaquil, Guayas → ❌ NO SE CREA ENVÍO (solo orden)
Cliente C: Vive en Manta, Manabí → ✅ SE CREA ENVÍO AUTOMÁTICO
```

## 🚚 **Cómo Asignar Repartidores - Guía Visual**

### Paso 1: Acceder al Panel de Envíos
```
URL: http://tu-dominio/admin/shipments
```

### Paso 2: Identificar Envíos Pendientes
En la tabla verás:
- **Tracking**: Número de seguimiento (ej: TRK-ABC123)
- **Orden**: #ID de la orden
- **Repartidor**: "Sin asignar" (para envíos pendientes)
- **Estado**: "Pendiente"
- **Acciones**: Botones de acción

### Paso 3: Asignar Repartidor
1. **Busca el envío** que dice "Sin asignar" en la columna Repartidor
2. **Busca el botón amarillo** 👤+ (icono de usuario con plus) en la columna Acciones
3. **Haz clic en el botón** 👤+
4. **Se abre un modal** con lista de repartidores disponibles
5. **Selecciona un repartidor** del dropdown
6. **Haz clic en "Asignar"**
7. **¡Listo!** El envío cambia a estado "ASIGNADO"

### Descripción Visual del Botón:
```
┌─────────────────────────────────────────────────────────┐
│ Tracking  │ Orden │ Repartidor  │ Estado    │ Acciones   │
├─────────────────────────────────────────────────────────┤
│ TRK-ABC123│ #123  │ Sin asignar │ Pendiente │ 👁️ ✏️ 👤+ 📍 🗑️ │
└─────────────────────────────────────────────────────────┘
                                              ↑
                                        ESTE BOTÓN
                                    (Amarillo, icono 👤+)
```

### Iconos de Acciones:
- 👁️ **Ver detalles** (azul)
- ✏️ **Editar** (verde) 
- 👤+ **Asignar Repartidor** (amarillo) ← **ESTE ES EL NUEVO**
- 📍 **Rastrear** (azul)
- 🗑️ **Eliminar** (rojo)

## 📱 **Estados del Envío**

Una vez asignado, el envío pasa por estos estados:

1. **PENDIENTE** → Recién creado, sin repartidor
2. **ASIGNADO** → Ya tiene repartidor asignado
3. **RECOGIDO** → Repartidor recogió el paquete
4. **EN TRÁNSITO** → En camino al destino
5. **ENTREGADO** → Entrega completada ✅
6. **FALLIDO** → No se pudo entregar ❌

## ⚙️ **Configuración del Sistema**

### Provincias Permitidas:
Solo se crean envíos automáticos para:
- ✅ Pichincha (Quito y alrededores)
- ✅ Manabí (Manta, Portoviejo, etc.)

### Cambiar Provincias:
Si quieres agregar más provincias, edita:
```php
// Archivo: app/Services/ShipmentService.php
private const ALLOWED_PROVINCES = ['Pichincha', 'Manabí', 'NuevaProvincia'];
```

## 🔧 **Solución de Problemas**

### "No veo el botón de asignar repartidor"
✅ **Verificar**:
- ¿El envío dice "Sin asignar"? (Si ya tiene repartidor, no aparece el botón)
- ¿Estás en la vista correcta? `/admin/shipments`
- ¿Has refrescado la página?

### "No aparecen envíos automáticos"
✅ **Verificar**:
- ¿La dirección del cliente está en Pichincha o Manabí?
- ¿Se completó correctamente el checkout?
- ¿Hay errores en los logs de Laravel?

### "Error al asignar repartidor"
✅ **Verificar**:
- ¿Existen repartidores activos en `/admin/delivery-drivers`?
- ¿El repartidor está marcado como "Activo"?

## 📊 **Reportes y Seguimiento**

### Ver Todos los Envíos:
```
URL: /admin/shipments
```

### Filtrar por Estado:
- Usa el dropdown "Estado" para ver solo pendientes, asignados, etc.

### Filtrar por Repartidor:
- Usa el dropdown "Repartidor" para ver envíos de un repartidor específico

### Buscar Envío:
- Usa la barra de búsqueda para buscar por:
  - Número de tracking
  - ID de orden
  - Nombre de cliente
  - Nombre de repartidor

## 🎯 **Flujo Completo de Ejemplo**

### Ejemplo Real:
1. **Juan** (cliente) vive en Quito, Pichincha
2. **Juan** hace un pedido de $50 y paga con tarjeta
3. **Sistema** verifica: Quito = Pichincha ✅ → Crea envío automático
4. **Admin** ve en `/admin/shipments`:
   ```
   TRK-XYZ789 | #456 | Sin asignar | Pendiente | [👤+ botón]
   ```
5. **Admin** hace clic en 👤+ y selecciona "Carlos - 0998765432"
6. **Estado cambia** a "ASIGNADO" y ya no aparece el botón 👤+
7. **Carlos** (repartidor) puede ver el envío en su panel
8. **Carlos** actualiza estados: RECOGIDO → EN TRÁNSITO → ENTREGADO

## ✅ **Verificación de Funcionamiento**

Para probar que todo funciona:

1. **Crear un pedido de prueba** con dirección en Quito
2. **Ir a** `/admin/shipments`
3. **Verificar** que aparece el envío con estado "Pendiente"
4. **Hacer clic** en el botón 👤+ (amarillo)
5. **Asignar** un repartidor
6. **Verificar** que el estado cambió a "Asignado"

---

**🎉 ¡Sistema funcionando correctamente!** 

Ahora tienes control total sobre el flujo de envíos desde la creación automática hasta la entrega final.
