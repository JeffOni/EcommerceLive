@extends('adminlte::page')

@section('title', 'Editar Envío')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Envío #{{ $shipment->tracking_number }}</h1>
        <a href="{{ route('admin.shipments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Información del Envío
                    </h3>
                </div>
                <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST" id="shipmentForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Orden asociada (solo lectura) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Orden Asociada</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            value="Orden #{{ $shipment->order->order_number }}" readonly>
                                        <div class="input-group-append">
                                            <a href="{{ route('admin.orders.show', $shipment->order) }}"
                                                class="btn btn-outline-info" target="_blank">
                                                <i class="fas fa-external-link-alt"></i> Ver Orden
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número de Seguimiento</label>
                                    <input type="text" class="form-control" value="{{ $shipment->tracking_number }}"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Repartidor -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery_driver_id">Repartidor <span class="text-danger">*</span></label>
                                    <select name="delivery_driver_id" id="delivery_driver_id"
                                        class="form-control @error('delivery_driver_id') is-invalid @enderror" required>
                                        <option value="">Seleccionar repartidor...</option>
                                        @foreach ($deliveryDrivers as $driver)
                                            <option value="{{ $driver->id }}"
                                                {{ old('delivery_driver_id', $shipment->delivery_driver_id) == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->name }} - {{ $driver->phone }}
                                                @if (!$driver->is_active)
                                                    (Inactivo)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('delivery_driver_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Estado <span class="text-danger">*</span></label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="pending"
                                            {{ old('status', $shipment->status) == 'pending' ? 'selected' : '' }}>
                                            Pendiente
                                        </option>
                                        <option value="picked_up"
                                            {{ old('status', $shipment->status) == 'picked_up' ? 'selected' : '' }}>
                                            Recogido
                                        </option>
                                        <option value="in_transit"
                                            {{ old('status', $shipment->status) == 'in_transit' ? 'selected' : '' }}>
                                            En Tránsito
                                        </option>
                                        <option value="out_for_delivery"
                                            {{ old('status', $shipment->status) == 'out_for_delivery' ? 'selected' : '' }}>
                                            En Reparto
                                        </option>
                                        <option value="delivered"
                                            {{ old('status', $shipment->status) == 'delivered' ? 'selected' : '' }}>
                                            Entregado
                                        </option>
                                        <option value="failed"
                                            {{ old('status', $shipment->status) == 'failed' ? 'selected' : '' }}>
                                            Falló
                                        </option>
                                        <option value="returned"
                                            {{ old('status', $shipment->status) == 'returned' ? 'selected' : '' }}>
                                            Devuelto
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fechas programadas -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scheduled_pickup_date">Fecha Programada de Recogida</label>
                                    <input type="datetime-local" name="scheduled_pickup_date" id="scheduled_pickup_date"
                                        class="form-control @error('scheduled_pickup_date') is-invalid @enderror"
                                        value="{{ old('scheduled_pickup_date', $shipment->scheduled_pickup_date?->format('Y-m-d\TH:i')) }}">
                                    @error('scheduled_pickup_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scheduled_delivery_date">Fecha Programada de Entrega</label>
                                    <input type="datetime-local" name="scheduled_delivery_date" id="scheduled_delivery_date"
                                        class="form-control @error('scheduled_delivery_date') is-invalid @enderror"
                                        value="{{ old('scheduled_delivery_date', $shipment->scheduled_delivery_date?->format('Y-m-d\TH:i')) }}">
                                    @error('scheduled_delivery_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fechas reales (para estados avanzados) -->
                        <div class="row" id="actualDatesSection"
                            style="{{ in_array($shipment->status, ['picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed', 'returned']) ? '' : 'display: none;' }}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="actual_pickup_date">Fecha Real de Recogida</label>
                                    <input type="datetime-local" name="actual_pickup_date" id="actual_pickup_date"
                                        class="form-control @error('actual_pickup_date') is-invalid @enderror"
                                        value="{{ old('actual_pickup_date', $shipment->actual_pickup_date?->format('Y-m-d\TH:i')) }}">
                                    @error('actual_pickup_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6" id="actualDeliverySection"
                                style="{{ in_array($shipment->status, ['delivered', 'failed', 'returned']) ? '' : 'display: none;' }}">
                                <div class="form-group">
                                    <label for="actual_delivery_date">Fecha Real de Entrega/Finalización</label>
                                    <input type="datetime-local" name="actual_delivery_date" id="actual_delivery_date"
                                        class="form-control @error('actual_delivery_date') is-invalid @enderror"
                                        value="{{ old('actual_delivery_date', $shipment->actual_delivery_date?->format('Y-m-d\TH:i')) }}">
                                    @error('actual_delivery_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="form-group">
                            <label for="notes">Notas / Observaciones</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Agregar notas sobre el envío...">{{ old('notes', $shipment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Envío
                        </button>
                        <a href="{{ route('admin.shipments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
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
                            <td><strong>Cliente:</strong></td>
                            <td>{{ $shipment->order->user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $shipment->order->user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td>${{ number_format($shipment->order->total, 2) }}</td>
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
                    </table>
                </div>
            </div>

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
                        <strong>Teléfono:</strong> {{ $shipment->order->shipping_phone }}
                    </address>
                </div>
            </div>

            <!-- Historial de estados -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i> Historial del Envío
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="time-label">
                            <span class="bg-primary">Creado: {{ $shipment->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($shipment->updated_at != $shipment->created_at)
                            <div class="time-label">
                                <span class="bg-info">Última actualización:
                                    {{ $shipment->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Mostrar/ocultar campos según el estado
            $('#status').on('change', function() {
                const status = $(this).val();
                const actualDatesSection = $('#actualDatesSection');
                const actualDeliverySection = $('#actualDeliverySection');

                if (['picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed', 'returned']
                    .includes(status)) {
                    actualDatesSection.show();
                } else {
                    actualDatesSection.hide();
                }

                if (['delivered', 'failed', 'returned'].includes(status)) {
                    actualDeliverySection.show();
                } else {
                    actualDeliverySection.hide();
                }

                // Auto-completar fecha de recogida si el estado es "picked_up" o posterior
                if (['picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed', 'returned']
                    .includes(status)) {
                    const actualPickupDate = $('#actual_pickup_date');
                    if (!actualPickupDate.val()) {
                        const now = new Date().toISOString().slice(0, 16);
                        actualPickupDate.val(now);
                    }
                }

                // Auto-completar fecha de entrega si el estado es "delivered", "failed" o "returned"
                if (['delivered', 'failed', 'returned'].includes(status)) {
                    const actualDeliveryDate = $('#actual_delivery_date');
                    if (!actualDeliveryDate.val()) {
                        const now = new Date().toISOString().slice(0, 16);
                        actualDeliveryDate.val(now);
                    }
                }
            });

            // Validación del formulario
            $('#shipmentForm').on('submit', function(e) {
                const status = $('#status').val();

                // Validar que si el estado requiere fecha de recogida, esté presente
                if (['picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed', 'returned']
                    .includes(status)) {
                    const actualPickupDate = $('#actual_pickup_date').val();
                    if (!actualPickupDate) {
                        e.preventDefault();
                        toastr.error('La fecha real de recogida es requerida para este estado.');
                        return false;
                    }
                }

                // Validar que si el estado requiere fecha de entrega, esté presente
                if (['delivered', 'failed', 'returned'].includes(status)) {
                    const actualDeliveryDate = $('#actual_delivery_date').val();
                    if (!actualDeliveryDate) {
                        e.preventDefault();
                        toastr.error(
                        'La fecha real de entrega/finalización es requerida para este estado.');
                        return false;
                    }
                }
            });
        });
    </script>
@stop
