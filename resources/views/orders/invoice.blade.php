<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            border-bottom: 2px solid #0066cc;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-info {
            float: left;
            width: 50%;
        }

        .invoice-info {
            float: right;
            width: 50%;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0066cc;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .customer-info,
        .shipping-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .products-table th {
            background-color: #0066cc;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        .products-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .products-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .totals {
            float: right;
            width: 40%;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .total-row.grand-total {
            font-weight: bold;
            font-size: 16px;
            border-bottom: 2px solid #0066cc;
            color: #0066cc;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-entregado {
            background-color: #d4edda;
            color: #155724;
        }

        .status-en-camino {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-preparando {
            background-color: #f3e2f3;
            color: #6f42c1;
        }

        .status-verificado {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .tracking-info {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #0066cc;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <h1 style="margin: 0; color: #0066cc;">Pescadería Tienda Online</h1>
            <p style="margin: 5px 0 0 0; color: #666;">
                Productos frescos del mar<br>
                Email: info@pescaderia.com<br>
                Teléfono: (02) 234-5678
            </p>
        </div>
        <div class="invoice-info">
            <h2 style="margin: 0; color: #333;">FACTURA</h2>
            <p style="margin: 5px 0 0 0;">
                <strong>Número:</strong> #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}<br>
                <strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y') }}<br>
                <strong>Estado:</strong>
                <span class="status-badge {{ $order->status == 6
                        ? 'status-entregado'
                        : ($order->status == 5
                            ? 'status-en-camino'
                            : ($order->status == 3
                                ? 'status-preparando'
                                : ($order->status == 2
                                    ? 'status-verificado'
                                    : 'status-pendiente'))) }}">
                    {{ $order->status_label }}
                </span>
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Información del Cliente -->
    <div class="section">
        <div class="section-title">Información del Cliente</div>
        <div class="customer-info">
            <strong>{{ $order->user->name }}</strong><br>
            Email: {{ $order->user->email }}<br>
            @if ($order->user->phone)
            Teléfono: {{ $order->user->phone }}<br>
            @endif
            Fecha de registro: {{ $order->user->created_at->format('d/m/Y') }}
        </div>
    </div>

    <!-- Dirección de Entrega -->
    <div class="section">
        <div class="section-title">Dirección de Entrega</div>
        <div class="shipping-info">
            @if ($order->shipping_address)
            <!-- Destinatario (nueva lógica simplificada) -->
            @if ($recipientInfo)
            <strong>Destinatario:</strong> {{ $recipientInfo['name'] }}
            @if ($recipientInfo['document'] && $recipientInfo['document'] !== 'No especificado')
            <span style="color: #666;">({{ $recipientInfo['document'] }})</span>
            @endif
            <br>

            @if ($recipientInfo['phone'] && $recipientInfo['phone'] !== 'No especificado')
            <strong>Teléfono:</strong> {{ $recipientInfo['phone'] }}<br>
            @endif

            @if (!empty($recipientInfo['email']))
            <strong>Email:</strong> {{ $recipientInfo['email'] }}<br>
            @endif

            <div
                style="background-color: #fef3c7; padding: 8px; margin: 8px 0; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <small style="color: #92400e;"><strong>Nota:</strong> Este pedido será entregado a un tercero diferente
                    al titular de la cuenta.</small>
            </div>
            @endif

            <!-- Dirección completa -->
            <strong>Dirección:</strong>
            @if (isset($order->shipping_address['address']))
            {{ is_array($order->shipping_address['address']) ? implode(', ', $order->shipping_address['address']) :
            $order->shipping_address['address'] }}
            @else
            No especificada
            @endif
            <br>

            <!-- Ubicación geográfica -->
            @if (isset($order->shipping_address['parish']) ||
            isset($order->shipping_address['canton']) ||
            isset($order->shipping_address['province']))
            <strong>Ubicación:</strong>
            @if (isset($order->shipping_address['parish']))
            {{ is_array($order->shipping_address['parish']) ? $order->shipping_address['parish']['name'] ?? '' :
            $order->shipping_address['parish'] }}
            @endif
            @if (isset($order->shipping_address['canton']))
            ,
            {{ is_array($order->shipping_address['canton']) ? $order->shipping_address['canton']['name'] ?? '' :
            $order->shipping_address['canton'] }}
            @endif
            @if (isset($order->shipping_address['province']))
            ,
            {{ is_array($order->shipping_address['province']) ? $order->shipping_address['province']['name'] ?? '' :
            $order->shipping_address['province'] }}
            @endif
            @if (isset($order->shipping_address['postal_code']) && $order->shipping_address['postal_code'])
            - CP:
            {{ is_array($order->shipping_address['postal_code']) ? $order->shipping_address['postal_code']['code'] ?? ''
            : $order->shipping_address['postal_code'] }}
            @endif
            <br>
            @endif

            <!-- Referencia -->
            @if (isset($order->shipping_address['reference']) && $order->shipping_address['reference'])
            <strong>Referencia:</strong>
            {{ is_array($order->shipping_address['reference']) ? implode(', ', $order->shipping_address['reference']) :
            $order->shipping_address['reference'] }}
            <br>
            @endif

            <!-- Notas adicionales -->
            @if (isset($order->shipping_address['notes']) && $order->shipping_address['notes'])
            <strong>Notas:</strong>
            {{ is_array($order->shipping_address['notes']) ? implode(', ', $order->shipping_address['notes']) :
            $order->shipping_address['notes'] }}<br>
            @endif

            <!-- Dirección completa formateada (si existe) -->
            @if (isset($order->shipping_address['full_address']) && $order->shipping_address['full_address'])
            <div style="margin-top: 10px; padding: 8px; background-color: #f8f9fa; border-left: 3px solid #007bff;">
                <strong>Dirección completa:</strong>
                {{ is_array($order->shipping_address['full_address']) ? implode(', ',
                $order->shipping_address['full_address']) : $order->shipping_address['full_address'] }}
            </div>
            @endif
            @else
            <em style="color: #666;">No se especificó dirección de envío</em>
            @endif
        </div>
    </div>

    <!-- Información de Envío -->
    @if ($order->shipment)
    <div class="section">
        <div class="section-title">Información de Envío</div>
        <div class="tracking-info">
            <strong>Número de Seguimiento:</strong> {{ $order->shipment->tracking_number }}<br>
            @if ($order->shipment->deliveryDriver)
            <strong>Repartidor:</strong> {{ $order->shipment->deliveryDriver->name }}<br>
            <strong>Teléfono del Repartidor:</strong> {{ $order->shipment->deliveryDriver->phone }}<br>
            @endif
            @if ($order->shipment->estimated_delivery_date)
            <strong>Fecha Estimada de Entrega:</strong>
            {{ $order->shipment->estimated_delivery_date->format('d/m/Y') }}<br>
            @endif
            @if ($order->shipment->delivered_at)
            <strong>Fecha de Entrega:</strong> {{ $order->shipment->delivered_at->format('d/m/Y H:i') }}
            @endif
        </div>
    </div>
    @endif

    <!-- Productos -->
    <div class="section">
        <div class="section-title">Productos Solicitados</div>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align: center;">Cantidad</th>
                    <th style="text-align: right;">Precio Unit.</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($order->content) && is_array($order->content))
                @foreach ($order->content as $item)
                <tr>
                    <td>
                        <strong>{{ is_array($item['name'] ?? '') ? implode(' ', $item['name']) : $item['name'] ??
                            'Producto' }}</strong>
                        @if (isset($item['options']) && !empty($item['options']))
                        <br><small style="color: #666;">
                            @foreach ($item['options'] as $key => $value)
                            {{ is_array($key) ? implode(' ', $key) : ucfirst($key) }}:
                            {{ is_array($value) ? implode(', ', $value) : $value }}
                            @endforeach
                        </small>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item['qty'] ?? 1 }}</td>
                    <td style="text-align: right;">${{ number_format($item['price'] ?? 0, 2) }}</td>
                    <td style="text-align: right;">
                        ${{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 2) }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4" style="text-align: center; color: #666;">
                        Información de productos no disponible
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Totales -->
    <div class="clear"></div>
    <div class="totals">
        @php
        $subtotal = 0;
        if (isset($order->content) && is_array($order->content)) {
        foreach ($order->content as $item) {
        $subtotal += ($item['price'] ?? 0) * ($item['qty'] ?? 1);
        }
        }
        $shipping = 5.0; // Costo de envío fijo
        $discount = 0; // Descuentos si aplican
        @endphp

        <div class="total-row">
            <span>Subtotal:</span>
            <span>${{ number_format($subtotal, 2) }}</span>
        </div>

        <div class="total-row">
            <span>Envío:</span>
            <span>${{ number_format($shipping, 2) }}</span>
        </div>

        @if ($discount > 0)
        <div class="total-row">
            <span>Descuento:</span>
            <span style="color: #dc3545;">-${{ number_format($discount, 2) }}</span>
        </div>
        @endif

        <div class="total-row grand-total">
            <span>TOTAL:</span>
            <span>${{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    <!-- Método de Pago -->
    <div class="clear"></div>
    <div class="section" style="margin-top: 40px;">
        <div class="section-title">Método de Pago</div>
        <p><strong>{{ $order->payment_method_label }}</strong></p>
        @if ($order->payment)
        @if ($order->payment->transaction_id)
        <p><small><strong>ID de Transacción:</strong> {{ $order->payment->transaction_id }}</small></p>
        @endif
        @if ($order->payment->verified_at)
        <p><small><strong>Pago Verificado el:</strong>
                {{ $order->payment->verified_at->format('d/m/Y H:i') }}</small></p>
        @endif
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>Pescadería Tienda Online</strong><br>
            Esta factura fue generada automáticamente el {{ now()->format('d/m/Y H:i') }}<br>
            Si tienes alguna pregunta sobre tu pedido, contáctanos en info@pescaderia.com
        </p>
        <p style="margin-top: 20px; font-size: 10px;">
            Documento generado digitalmente - No requiere firma física
        </p>
    </div>
</body>

</html>