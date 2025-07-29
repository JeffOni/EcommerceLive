<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $order->id }}</title>
    @php
    $logoPath = public_path('img/logo.png');
    $logoBase64 = '';

    if (file_exists($logoPath)) {
    try {
    $logoBase64 = base64_encode(file_get_contents($logoPath));
    } catch (Exception $e) {
    $logoBase64 = '';
    }
    }
    @endphp
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 30px;
            background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }

        .company-info {
            float: left;
            width: 65%;
        }

        .company-logo {
            display: inline-block;
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 8px;
            margin-right: 15px;
            vertical-align: middle;
            text-align: center;
            line-height: 80px;
            font-size: 32px;
            color: #1e40af;
            font-weight: bold;
            overflow: hidden;
        }

        .company-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .invoice-info {
            float: right;
            width: 30%;
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
            color: #1e40af;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 8px;
            margin-bottom: 15px;
            background: linear-gradient(90deg, rgba(30, 64, 175, 0.1) 0%, transparent 100%);
            padding-left: 10px;
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
            background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        .products-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: top;
        }

        .products-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 10px;
            float: left;
            border: 1px solid #e5e7eb;
        }

        .product-details {
            overflow: hidden;
            min-height: 50px;
        }

        .product-details strong {
            display: block;
            margin-bottom: 4px;
            line-height: 1.3;
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
            border-bottom: 2px solid #1e40af;
            color: #1e40af;
            background: linear-gradient(90deg, rgba(30, 64, 175, 0.1) 0%, transparent 100%);
            padding: 12px 8px;
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
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .status-en-camino {
            background: linear-gradient(135deg, #1e40af, #7c3aed);
            color: white;
        }

        .status-preparando {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: white;
        }

        .status-verificado {
            background: linear-gradient(135deg, #0ea5e9, #06b6d4);
            color: white;
        }

        .status-pendiente {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 2px solid #1e40af;
            padding-top: 20px;
        }

        .tracking-info {
            background: linear-gradient(90deg, rgba(30, 64, 175, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #1e40af;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <div class="company-logo">
                @if($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Lago Fish Logo"
                    style="width: 100%; height: 100%; object-fit: contain;">
                @else
                <span style="color: #1e40af; font-size: 24px; font-weight: bold;">LF</span>
                @endif
            </div>
            <div style="display: inline-block; vertical-align: middle;">
                <h1 style="margin: 0; color: white; font-size: 28px; font-weight: bold;">Lago Fish</h1>
                <p style="margin: 2px 0 0 0; color: rgba(255,255,255,0.9); font-size: 13px; line-height: 1.4;">
                    <strong>El Novio y el Pez Frescos Deben Ser</strong><br>
                    RUC: 0992345678001<br>
                    Dirección: Manta - Ecuador<br>
                    Email: servicioalcliente@lagofish.store<br>
                    WhatsApp: +593 99 980 5450
                </p>
            </div>
        </div>
        <div class="invoice-info">
            <h2 style="margin: 0; color: white; font-size: 24px;">FACTURA</h2>
            <p style="margin: 5px 0 0 0; color: rgba(255,255,255,0.9);">
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
                style="background: linear-gradient(90deg, rgba(30, 64, 175, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%); padding: 12px; margin: 8px 0; border-left: 4px solid #1e40af; border-radius: 8px;">
                <small style="color: #1e40af;"><strong>Nota:</strong> Este pedido será entregado a un tercero diferente
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
            <div
                style="margin-top: 10px; padding: 12px; background: linear-gradient(90deg, rgba(30, 64, 175, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%); border-left: 4px solid #1e40af; border-radius: 8px;">
                <strong style="color: #1e40af;">Dirección completa:</strong>
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
                @php
                $orderContent = is_string($order->content) ? json_decode($order->content, true) : $order->content;
                $products = [];

                if (isset($orderContent['products']) && is_array($orderContent['products'])) {
                $products = $orderContent['products'];
                } elseif (is_array($order->content)) {
                $products = $order->content;
                }
                @endphp

                @forelse ($products as $item)
                <tr>
                    <td>
                        <div class="product-details">
                            @if (isset($item['image']) && $item['image'] && !empty(trim($item['image'])))
                            @php
                            $imagePath = public_path('storage/' . ltrim($item['image'], '/storage/'));
                            $imageBase64 = '';
                            if (file_exists($imagePath)) {
                            try {
                            $imageBase64 = base64_encode(file_get_contents($imagePath));
                            } catch (Exception $e) {
                            $imageBase64 = '';
                            }
                            }
                            @endphp
                            @if($imageBase64)
                            <img src="data:image/jpeg;base64,{{ $imageBase64 }}" alt="Producto" class="product-image">
                            @endif
                            @endif

                            <strong>{{ $item['name'] ?? 'Producto' }}</strong>

                            @if (isset($item['sku']) && !empty($item['sku']) && $item['sku'] != 'N/A')
                            <br><small style="color: #666;">SKU: {{ $item['sku'] }}</small>
                            @endif
                        </div>
                    </td>
                    <td style="text-align: center;">{{ $item['qty'] ?? 1 }}</td>
                    <td style="text-align: right;">${{ number_format($item['price'] ?? 0, 2) }}</td>
                    <td style="text-align: right;">
                        ${{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #666; padding: 20px;">
                        No hay productos en esta orden
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Totales -->
    <div class="clear"></div>
    <div class="totals">
        @php
        $subtotal = 0;
        $orderContent = is_string($order->content) ? json_decode($order->content, true) : $order->content;
        $products = [];

        if (isset($orderContent['products']) && is_array($orderContent['products'])) {
        $products = $orderContent['products'];
        } elseif (is_array($order->content)) {
        $products = $order->content;
        }

        foreach ($products as $item) {
        $subtotal += ($item['price'] ?? 0) * ($item['qty'] ?? 1);
        }

        $shipping = $order->shipping_cost ?? 5.0;
        $discount = $order->discount ?? 0;
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
        <div style="margin-bottom: 15px;">
            <div
                style="display: inline-block; width: 40px; height: 40px; background: white; border-radius: 6px; text-align: center; margin-right: 10px; padding: 3px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                @if($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Lago Fish"
                    style="width: 100%; height: 100%; object-fit: contain;">
                @else
                <span style="color: #1e40af; font-size: 16px; font-weight: bold; line-height: 34px;">LF</span>
                @endif
            </div>
            <strong style="color: #1e40af; font-size: 16px;">Lago Fish</strong>
        </div>
        <p style="color: #666; margin: 10px 0; text-align: center;">
            Esta factura fue generada automáticamente el {{ now()->format('d/m/Y H:i') }}<br>
            Si tienes alguna pregunta sobre tu pedido, contáctanos en <strong
                style="color: #1e40af;">servicioalcliente@lagofish.store</strong><br>
            <span style="font-size: 11px;">Teléfono: (04) 234-5678 | WhatsApp: +593 99 123 4567</span>
        </p>
        <p style="margin-top: 20px; font-size: 10px; color: #999; text-align: center;">
            Documento generado digitalmente - No requiere firma física<br>
            <span style="color: #1e40af;">Lago Fish - RUC: 0992345678001 - Av. de los Mariscos 456, Guayaquil -
                Ecuador</span>
        </p>
    </div>
</body>

</html>