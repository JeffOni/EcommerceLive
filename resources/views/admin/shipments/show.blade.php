@extends('adminlte::page')

@section('title', 'Detalles del Envío')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Envío #{{ $shipment->tracking_number }}</h1>
    <div>
        <a href="{{ route('admin.shipments.edit', $shipment) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('admin.shipments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Información principal del envío -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shipping-fast"></i> Información del Envío
                </h3>
                <div class="card-tools">
                    <span class="badge badge-lg badge-{{ $shipment->status == 'pending'
                                ? 'warning'
                                : ($shipment->status == 'picked_up'
                                    ? 'info'
                                    : ($shipment->status == 'in_transit'
                                        ? 'primary'
                                        : ($shipment->status == 'out_for_delivery'
                                            ? 'warning'
                                            : ($shipment->status == 'delivered'
                                                ? 'success'
                                                : ($shipment->status == 'failed'
                                                    ? 'danger'
                                                    : 'secondary'))))) }}">
                        {{ ucfirst(str_replace('_', ' ', $shipment->status->value)) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td width="40%"><strong>Número de Seguimiento:</strong></td>
                                <td>{{ $shipment->tracking_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Orden Asociada:</strong></td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $shipment->order) }}" class="text-primary">
                                        Orden #{{ $shipment->order->order_number }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Repartidor:</strong></td>
                                <td>
                                    @if ($shipment->deliveryDriver)
                                    <a href="{{ route('admin.delivery-drivers.show', $shipment->deliveryDriver) }}"
                                        class="text-primary">
                                        {{ $shipment->deliveryDriver->name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $shipment->deliveryDriver->phone }}</small>
                                    @else
                                    <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Estado:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $shipment->status == 'pending'
                                                ? 'warning'
                                                : ($shipment->status == 'picked_up'
                                                    ? 'info'
                                                    : ($shipment->status == 'in_transit'
                                                        ? 'primary'
                                                        : ($shipment->status == 'out_for_delivery'
                                                            ? 'warning'
                                                            : ($shipment->status == 'delivered'
                                                                ? 'success'
                                                                : ($shipment->status == 'failed'
                                                                    ? 'danger'
                                                                    : 'secondary'))))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status->value)) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td width="40%"><strong>Creado:</strong></td>
                                <td>{{ $shipment->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Última Actualización:</strong></td>
                                <td>{{ $shipment->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fecha Prog. Recogida:</strong></td>
                                <td>
                                    @if ($shipment->scheduled_pickup_date)
                                    {{ $shipment->scheduled_pickup_date->format('d/m/Y H:i') }}
                                    @else
                                    <span class="text-muted">No programada</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Fecha Prog. Entrega:</strong></td>
                                <td>
                                    @if ($shipment->scheduled_delivery_date)
                                    {{ $shipment->scheduled_delivery_date->format('d/m/Y H:i') }}
                                    @else
                                    <span class="text-muted">No programada</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if ($shipment->notes)
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-sticky-note"></i> Notas:</h6>
                            <p class="mb-0">{{ $shipment->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Línea de tiempo del envío -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-timeline"></i> Línea de Tiempo del Envío
                </h3>
            </div>
            <div class="card-body">
                <div class="timeline timeline-inverse">
                    <!-- Envío creado -->
                    <div class="time-label">
                        <span class="bg-primary">
                            {{ $shipment->created_at->format('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-plus bg-primary"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i>
                                {{ $shipment->created_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Envío Creado</h3>
                            <div class="timeline-body">
                                El envío fue registrado en el sistema con el número de seguimiento
                                {{ $shipment->tracking_number }}.
                            </div>
                        </div>
                    </div>

                    @if ($shipment->actual_pickup_date)
                    <!-- Recogida realizada -->
                    <div class="time-label">
                        <span class="bg-info">
                            {{ $shipment->actual_pickup_date->format('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-box bg-info"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i>
                                {{ $shipment->actual_pickup_date->format('H:i') }}</span>
                            <h3 class="timeline-header">Paquete Recogido</h3>
                            <div class="timeline-body">
                                El paquete fue recogido por
                                {{ $shipment->deliveryDriver ? $shipment->deliveryDriver->name : 'el repartidor' }}.
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($shipment->status == 'in_transit')
                    <!-- En tránsito -->
                    <div>
                        <i class="fas fa-truck bg-warning"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> Ahora</span>
                            <h3 class="timeline-header">En Tránsito</h3>
                            <div class="timeline-body">
                                El paquete está en camino hacia su destino.
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($shipment->status == 'out_for_delivery')
                    <!-- En reparto -->
                    <div>
                        <i class="fas fa-shipping-fast bg-orange"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> Ahora</span>
                            <h3 class="timeline-header">En Reparto</h3>
                            <div class="timeline-body">
                                El paquete está siendo entregado al cliente.
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($shipment->actual_delivery_date)
                    <!-- Entrega realizada -->
                    <div class="time-label">
                        <span
                            class="bg-{{ $shipment->status == 'delivered' ? 'success' : ($shipment->status == 'failed' ? 'danger' : 'secondary') }}">
                            {{ $shipment->actual_delivery_date->format('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <i
                            class="fas fa-{{ $shipment->status == 'delivered' ? 'check' : ($shipment->status == 'failed' ? 'times' : 'undo') }} bg-{{ $shipment->status == 'delivered' ? 'success' : ($shipment->status == 'failed' ? 'danger' : 'secondary') }}"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i>
                                {{ $shipment->actual_delivery_date->format('H:i') }}</span>
                            <h3 class="timeline-header">
                                @if ($shipment->status == 'delivered')
                                Entregado Exitosamente
                                @elseif($shipment->status == 'failed')
                                Entrega Fallida
                                @else
                                Devuelto
                                @endif
                            </h3>
                            <div class="timeline-body">
                                @if ($shipment->status == 'delivered')
                                El paquete fue entregado exitosamente al cliente.
                                @elseif($shipment->status == 'failed')
                                No fue posible entregar el paquete al cliente.
                                @else
                                El paquete fue devuelto al remitente.
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Fin de timeline -->
                    <div>
                        <i class="far fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Información de la orden -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shopping-cart"></i> Información de la Orden
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Número:</strong></td>
                        <td>
                            <a href="{{ route('admin.orders.show', $shipment->order) }}" class="text-primary">
                                #{{ $shipment->order->order_number }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Cliente:</strong></td>
                        <td>{{ $shipment->order->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>
                            <a href="mailto:{{ $shipment->order->user->email }}">
                                {{ $shipment->order->user->email }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Total:</strong></td>
                        <td><strong>${{ number_format($shipment->order->total, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Estado Orden:</strong></td>
                        <td>
                            <span
                                class="badge badge-{{ $shipment->order->status == 'pending' ? 'warning' : ($shipment->order->status == 'paid' ? 'success' : 'secondary') }}">
                                {{ ucfirst($shipment->order->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Método de Pago:</strong></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $shipment->order->payment_method)) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Información del repartidor -->
        @if ($shipment->deliveryDriver)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i> Repartidor Asignado
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if ($shipment->deliveryDriver->avatar)
                    <img src="{{ asset('storage/' . $shipment->deliveryDriver->avatar) }}"
                        class="img-circle elevation-2" width="80" height="80" alt="Avatar">
                    @else
                    <div class="img-circle elevation-2 d-inline-flex align-items-center justify-content-center bg-secondary text-white"
                        style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($shipment->deliveryDriver->name, 0, 1) }}
                    </div>
                    @endif
                </div>
                <table class="table table-sm">
                    <tr>
                        <td><strong>Nombre:</strong></td>
                        <td>
                            <a href="{{ route('admin.delivery-drivers.show', $shipment->deliveryDriver) }}"
                                class="text-primary">
                                {{ $shipment->deliveryDriver->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Teléfono:</strong></td>
                        <td>
                            <a href="tel:{{ $shipment->deliveryDriver->phone }}">
                                {{ $shipment->deliveryDriver->phone }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>
                            <a href="mailto:{{ $shipment->deliveryDriver->email }}">
                                {{ $shipment->deliveryDriver->email }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Estado:</strong></td>
                        <td>
                            <span
                                class="badge badge-{{ $shipment->deliveryDriver->is_active ? 'success' : 'secondary' }}">
                                {{ $shipment->deliveryDriver->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        <!-- Información de entrega -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt"></i> Dirección de Entrega
                </h3>
            </div>
            <div class="card-body">
                <address>
                    <strong>{{ $shipment->order->shipping_name }}</strong><br>
                    {{ $shipment->order->shipping_address }}<br>
                    {{ $shipment->order->shipping_city }}, {{ $shipment->order->shipping_state }}<br>
                    {{ $shipment->order->shipping_postal_code }}<br>
                    @if ($shipment->order->shipping_phone)
                    <strong>Teléfono:</strong>
                    <a href="tel:{{ $shipment->order->shipping_phone }}">
                        {{ $shipment->order->shipping_phone }}
                    </a>
                    @endif
                </address>

                <!-- Botón para abrir en Google Maps -->
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($shipment->order->shipping_address . ', ' . $shipment->order->shipping_city . ', ' . $shipment->order->shipping_state) }}"
                    target="_blank" class="btn btn-sm btn-info">
                    <i class="fas fa-map"></i> Ver en Google Maps
                </a>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i> Acciones Rápidas
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.shipments.edit', $shipment) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Editar Envío
                    </a>

                    @if ($shipment->status == 'pending')
                    <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="picked_up">
                        <button type="submit" class="btn btn-info btn-block"
                            onclick="return confirm('¿Marcar como recogido?')">
                            <i class="fas fa-box"></i> Marcar como Recogido
                        </button>
                    </form>
                    @endif

                    @if ($shipment->status == 'picked_up')
                    <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="in_transit">
                        <button type="submit" class="btn btn-primary btn-block"
                            onclick="return confirm('¿Marcar como en tránsito?')">
                            <i class="fas fa-truck"></i> Marcar En Tránsito
                        </button>
                    </form>
                    @endif

                    @if ($shipment->status == 'in_transit')
                    <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="out_for_delivery">
                        <button type="submit" class="btn btn-warning btn-block"
                            onclick="return confirm('¿Marcar como en reparto?')">
                            <i class="fas fa-shipping-fast"></i> Marcar En Reparto
                        </button>
                    </form>
                    @endif

                    @if ($shipment->status == 'out_for_delivery')
                    <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="btn btn-success btn-block"
                            onclick="return confirm('¿Marcar como entregado?')">
                            <i class="fas fa-check"></i> Marcar como Entregado
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop