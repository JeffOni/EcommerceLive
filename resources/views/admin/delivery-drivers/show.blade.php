@extends('adminlte::page')

@section('title', 'Detalles del Repartidor')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Detalles del Repartidor</h1>
    <div>
        <a href="{{ route('admin.delivery-drivers.edit', $deliveryDriver) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('admin.delivery-drivers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Información principal del repartidor -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i> Información Personal
                </h3>
                <div class="card-tools">
                    <span class="badge badge-lg badge-{{ $deliveryDriver->is_active ? 'success' : 'secondary' }}">
                        {{ $deliveryDriver->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        @if ($deliveryDriver->avatar)
                        <img src="{{ asset('storage/' . $deliveryDriver->avatar) }}" class="img-circle elevation-2"
                            width="120" height="120" alt="Avatar">
                        @else
                        <div class="img-circle elevation-2 d-inline-flex align-items-center justify-content-center bg-secondary text-white"
                            style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ substr($deliveryDriver->name, 0, 1) }}
                        </div>
                        @endif
                        <h4 class="mt-2">{{ $deliveryDriver->name }}</h4>
                        <p class="text-muted">
                            {{ $deliveryDriver->vehicle_type ? ucfirst(str_replace('_', ' ',
                            $deliveryDriver->vehicle_type)) : 'Sin vehículo asignado' }}
                        </p>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Email:</strong></td>
                                        <td>
                                            <a href="mailto:{{ $deliveryDriver->email }}">
                                                {{ $deliveryDriver->email }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Teléfono:</strong></td>
                                        <td>
                                            <a href="tel:{{ $deliveryDriver->phone }}">
                                                {{ $deliveryDriver->phone }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Licencia:</strong></td>
                                        <td>{{ $deliveryDriver->license_number ?? 'No especificada' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Zona:</strong></td>
                                        <td>{{ $deliveryDriver->zone ?? 'No especificada' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Tipo de Vehículo:</strong></td>
                                        <td>{{ $deliveryDriver->vehicle_type ? ucfirst(str_replace('_', ' ',
                                            $deliveryDriver->vehicle_type)) : 'No especificado' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Placa:</strong></td>
                                        <td>{{ $deliveryDriver->vehicle_plate ?? 'No especificada' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Registrado:</strong></td>
                                        <td>{{ $deliveryDriver->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado:</strong></td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $deliveryDriver->is_active ? 'success' : 'secondary' }}">
                                                {{ $deliveryDriver->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($deliveryDriver->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-sticky-note"></i> Notas:</h6>
                                    <p class="mb-0">{{ $deliveryDriver->notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de envíos -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shipping-fast"></i> Historial de Envíos
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.shipments.index', ['driver_id' => $deliveryDriver->id]) }}"
                        class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Ver Todos
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($deliveryDriver->shipments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Tracking</th>
                                <th>Orden</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deliveryDriver->shipments->take(10) as $shipment)
                            <tr>
                                <td>
                                    <span class="font-weight-bold">{{ $shipment->tracking_number }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $shipment->order) }}" class="text-primary">
                                        #{{ $shipment->order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $shipment->order->user->name }}</td>
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
                                <td>{{ $shipment->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.shipments.show', $shipment) }}"
                                        class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($deliveryDriver->shipments->count() > 10)
                <div class="text-center mt-3">
                    <a href="{{ route('admin.shipments.index', ['driver_id' => $deliveryDriver->id]) }}"
                        class="btn btn-sm btn-outline-info">
                        Ver todos los {{ $deliveryDriver->shipments->count() }} envíos
                    </a>
                </div>
                @endif
                @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted"></i>
                    <p class="mt-2 text-muted">No hay envíos asignados a este repartidor</p>
                    <a href="{{ route('admin.shipments.create', ['driver_id' => $deliveryDriver->id]) }}"
                        class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primer Envío
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Estadísticas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i> Estadísticas de Rendimiento
                </h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <span class="description-percentage text-info">
                                <i class="fas fa-shipping-fast"></i>
                            </span>
                            <h5 class="description-header">{{ $deliveryDriver->shipments->count() }}</h5>
                            <span class="description-text">TOTAL ENVÍOS</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-success">
                                <i class="fas fa-check"></i>
                            </span>
                            <h5 class="description-header">
                                {{ $deliveryDriver->shipments->where('status', 'delivered')->count() }}</h5>
                            <span class="description-text">ENTREGADOS</span>
                        </div>
                    </div>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <span class="description-percentage text-warning">
                                <i class="fas fa-clock"></i>
                            </span>
                            <h5 class="description-header">
                                {{ $deliveryDriver->shipments->whereIn('status', ['pending', 'picked_up', 'in_transit',
                                'out_for_delivery'])->count() }}
                            </h5>
                            <span class="description-text">EN PROCESO</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-danger">
                                <i class="fas fa-times"></i>
                            </span>
                            <h5 class="description-header">
                                {{ $deliveryDriver->shipments->where('status', 'failed')->count() }}</h5>
                            <span class="description-text">FALLIDOS</span>
                        </div>
                    </div>
                </div>

                @if ($deliveryDriver->shipments->count() > 0)
                @php
                $successRate =
                $deliveryDriver->shipments->count() > 0
                ? round(
                ($deliveryDriver->shipments->where('status', 'delivered')->count() /
                $deliveryDriver->shipments->count()) *
                100,
                1,
                )
                : 0;
                @endphp
                <div class="mt-4">
                    <div class="d-flex justify-content-between">
                        <span>Tasa de Éxito</span>
                        <span>{{ $successRate }}%</span>
                    </div>
                    <div class="progress progress-sm mt-2">
                        <div class="progress-bar bg-{{ $successRate >= 80 ? 'success' : ($successRate >= 60 ? 'warning' : 'danger') }}"
                            style="width: {{ $successRate }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Información de contacto -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-address-card"></i> Información de Contacto
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="tel:{{ $deliveryDriver->phone }}" class="btn btn-success btn-block">
                        <i class="fas fa-phone"></i> Llamar: {{ $deliveryDriver->phone }}
                    </a>

                    <a href="mailto:{{ $deliveryDriver->email }}" class="btn btn-info btn-block">
                        <i class="fas fa-envelope"></i> Enviar Email
                    </a>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $deliveryDriver->phone) }}" target="_blank"
                        class="btn btn-success btn-block">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Información del vehículo -->
        @if ($deliveryDriver->vehicle_type)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-car"></i> Información del Vehículo
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Tipo:</strong></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $deliveryDriver->vehicle_type)) }}</td>
                    </tr>
                    @if ($deliveryDriver->vehicle_plate)
                    <tr>
                        <td><strong>Placa:</strong></td>
                        <td>{{ $deliveryDriver->vehicle_plate }}</td>
                    </tr>
                    @endif
                    @if ($deliveryDriver->license_number)
                    <tr>
                        <td><strong>Licencia:</strong></td>
                        <td>{{ $deliveryDriver->license_number }}</td>
                    </tr>
                    @endif
                    @if ($deliveryDriver->zone)
                    <tr>
                        <td><strong>Zona:</strong></td>
                        <td>{{ $deliveryDriver->zone }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif

        <!-- Acciones rápidas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i> Acciones Rápidas
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.delivery-drivers.edit', $deliveryDriver) }}"
                        class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Editar Información
                    </a>

                    <form action="{{ route('admin.delivery-drivers.toggle-status', $deliveryDriver) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="btn btn-{{ $deliveryDriver->is_active ? 'warning' : 'success' }} btn-block"
                            onclick="return confirm('¿Estás seguro de {{ $deliveryDriver->is_active ? 'desactivar' : 'activar' }} este repartidor?')">
                            <i class="fas fa-{{ $deliveryDriver->is_active ? 'pause' : 'play' }}"></i>
                            {{ $deliveryDriver->is_active ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>

                    <a href="{{ route('admin.shipments.create', ['driver_id' => $deliveryDriver->id]) }}"
                        class="btn btn-info btn-block">
                        <i class="fas fa-plus"></i> Crear Envío
                    </a>

                    <a href="{{ route('admin.shipments.index', ['driver_id' => $deliveryDriver->id]) }}"
                        class="btn btn-secondary btn-block">
                        <i class="fas fa-list"></i> Ver Todos los Envíos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop