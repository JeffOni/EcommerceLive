@extends('adminlte::page')

@section('title', 'Editar Repartidor')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Repartidor</h1>
        <a href="{{ route('admin.delivery-drivers.index') }}" class="btn btn-secondary">
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
                        <i class="fas fa-edit"></i> Información del Repartidor
                    </h3>
                </div>
                <form action="{{ route('admin.delivery-drivers.update', $deliveryDriver) }}" method="POST"
                    enctype="multipart/form-data" id="driverForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Avatar -->
                        <div class="form-group text-center mb-4">
                            <label>Avatar</label>
                            <div class="mb-3">
                                @if ($deliveryDriver->avatar)
                                    <img src="{{ asset('storage/' . $deliveryDriver->avatar) }}"
                                        class="img-circle elevation-2" width="120" height="120" alt="Avatar actual"
                                        id="currentAvatar">
                                @else
                                    <div class="img-circle elevation-2 d-inline-flex align-items-center justify-content-center bg-secondary text-white"
                                        style="width: 120px; height: 120px; font-size: 3rem;" id="currentAvatar">
                                        {{ substr($deliveryDriver->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="custom-file" style="max-width: 300px; margin: 0 auto;">
                                <input type="file" name="avatar" id="avatar"
                                    class="custom-file-input @error('avatar') is-invalid @enderror" accept="image/*"
                                    onchange="previewImage(this)">
                                <label class="custom-file-label" for="avatar">Seleccionar nueva imagen...</label>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</small>
                        </div>

                        <!-- Información básica -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $deliveryDriver->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $deliveryDriver->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Teléfono <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $deliveryDriver->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="license_number">Número de Licencia</label>
                                    <input type="text" name="license_number" id="license_number"
                                        class="form-control @error('license_number') is-invalid @enderror"
                                        value="{{ old('license_number', $deliveryDriver->license_number) }}">
                                    @error('license_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información del vehículo -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vehicle_type">Tipo de Vehículo</label>
                                    <select name="vehicle_type" id="vehicle_type"
                                        class="form-control @error('vehicle_type') is-invalid @enderror">
                                        <option value="">Seleccionar...</option>
                                        <option value="motorcycle"
                                            {{ old('vehicle_type', $deliveryDriver->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>
                                            Motocicleta</option>
                                        <option value="bicycle"
                                            {{ old('vehicle_type', $deliveryDriver->vehicle_type) == 'bicycle' ? 'selected' : '' }}>
                                            Bicicleta</option>
                                        <option value="car"
                                            {{ old('vehicle_type', $deliveryDriver->vehicle_type) == 'car' ? 'selected' : '' }}>
                                            Automóvil</option>
                                        <option value="van"
                                            {{ old('vehicle_type', $deliveryDriver->vehicle_type) == 'van' ? 'selected' : '' }}>
                                            Furgoneta</option>
                                        <option value="truck"
                                            {{ old('vehicle_type', $deliveryDriver->vehicle_type) == 'truck' ? 'selected' : '' }}>
                                            Camión</option>
                                        <option value="walking"
                                            {{ old('vehicle_type', $deliveryDriver->vehicle_type) == 'walking' ? 'selected' : '' }}>
                                            A pie</option>
                                    </select>
                                    @error('vehicle_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vehicle_plate">Placa del Vehículo</label>
                                    <input type="text" name="vehicle_plate" id="vehicle_plate"
                                        class="form-control @error('vehicle_plate') is-invalid @enderror"
                                        value="{{ old('vehicle_plate', $deliveryDriver->vehicle_plate) }}">
                                    @error('vehicle_plate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_active">Estado</label>
                                    <select name="is_active" id="is_active"
                                        class="form-control @error('is_active') is-invalid @enderror">
                                        <option value="1"
                                            {{ old('is_active', $deliveryDriver->is_active) == '1' ? 'selected' : '' }}>
                                            Activo</option>
                                        <option value="0"
                                            {{ old('is_active', $deliveryDriver->is_active) == '0' ? 'selected' : '' }}>
                                            Inactivo</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Zona de trabajo -->
                        <div class="form-group">
                            <label for="zone">Zona de Trabajo</label>
                            <input type="text" name="zone" id="zone"
                                class="form-control @error('zone') is-invalid @enderror"
                                value="{{ old('zone', $deliveryDriver->zone) }}" placeholder="Ej: Centro, Norte, Sur...">
                            @error('zone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notas -->
                        <div class="form-group">
                            <label for="notes">Notas / Observaciones</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Agregar notas sobre el repartidor...">{{ old('notes', $deliveryDriver->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Repartidor
                        </button>
                        <a href="{{ route('admin.delivery-drivers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Estadísticas del repartidor -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> Estadísticas
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
                                    {{ $deliveryDriver->shipments->whereIn('status', ['pending', 'picked_up', 'in_transit', 'out_for_delivery'])->count() }}
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
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Información Adicional
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Registrado:</strong></td>
                            <td>{{ $deliveryDriver->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Última actualización:</strong></td>
                            <td>{{ $deliveryDriver->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td>
                                <span class="badge badge-{{ $deliveryDriver->is_active ? 'success' : 'secondary' }}">
                                    {{ $deliveryDriver->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                    </table>
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
                        <a href="{{ route('admin.delivery-drivers.show', $deliveryDriver) }}"
                            class="btn btn-info btn-block">
                            <i class="fas fa-eye"></i> Ver Detalles
                        </a>

                        <form action="{{ route('admin.delivery-drivers.toggle-status', $deliveryDriver) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="btn btn-{{ $deliveryDriver->is_active ? 'warning' : 'success' }} btn-block"
                                onclick="return confirm('¿Estás seguro de {{ $deliveryDriver->is_active ? 'desactivar' : 'activar' }} este repartidor?')">
                                <i class="fas fa-{{ $deliveryDriver->is_active ? 'pause' : 'play' }}"></i>
                                {{ $deliveryDriver->is_active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.shipments.index', ['driver_id' => $deliveryDriver->id]) }}"
                            class="btn btn-secondary btn-block">
                            <i class="fas fa-shipping-fast"></i> Ver Envíos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#currentAvatar').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);

                // Actualizar el label del archivo
                const fileName = input.files[0].name;
                $(input).next('.custom-file-label').html(fileName);
            }
        }

        $(document).ready(function() {
            // Validación del formulario
            $('#driverForm').on('submit', function(e) {
                // Aquí puedes agregar validaciones adicionales si es necesario
            });

            // Actualizar label del archivo cuando se selecciona
            $('.custom-file-input').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
@stop
