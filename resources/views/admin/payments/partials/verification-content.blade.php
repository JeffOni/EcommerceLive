<!-- Vista de Tarjetas -->
<div id="cards-view" class="view-content p-3 sm:p-6">
    @if($payments->isEmpty())
    <div class="text-center py-12">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-4">
            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">¡Excelente!</h3>
        <p class="text-gray-500">No hay comprobantes pendientes de verificación.</p>
        <a href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center px-4 py-2 mt-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-shopping-cart mr-2"></i> Ver Órdenes
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach($payments as $payment)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <!-- Header de la tarjeta -->
            <div class="bg-gradient-to-r from-primary-600 to-secondary-600 px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-receipt text-white text-sm"></i>
                        <span class="text-white font-semibold text-sm">${{ number_format($payment->amount, 2) }}</span>
                    </div>
                    <span
                        class="px-2 py-1 rounded-full text-xs font-semibold {{ $payment->payment_method == 'bank_transfer' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                        {{ $payment->payment_method == 'bank_transfer' ? 'Transferencia' : ($payment->payment_method ==
                        'qr_deuna' ? 'QR DeUna' : 'QR PayPhone') }}
                    </span>
                </div>
            </div>

            <!-- Contenido de la tarjeta -->
            <div class="p-4 space-y-3">
                <!-- Información del usuario -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xs"></i>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $payment->user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $payment->user->email }}</p>
                    </div>
                </div>

                <!-- Información del pago -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Estado:</span>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            {{ $payment->status == 'approved' ? 'bg-green-100 text-green-800' : 
                               ($payment->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $payment->status == 'approved' ? '✅ Aprobado' :
                            ($payment->status == 'rejected' ? '❌ Rechazado' : '🟡 Pendiente') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500">Orden:</span>
                        <span class="font-medium text-xs text-blue-600">#{{ $payment->order->id ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="text-xs text-gray-500">
                    <i class="fas fa-calendar mr-1"></i>{{ $payment->created_at->format('d/m/Y H:i') }}
                </div>

                @if($payment->transaction_id)
                <div class="text-xs text-gray-600">
                    <span class="font-medium">ID:</span> {{ Str::limit($payment->transaction_id, 20) }}
                </div>
                @endif

                <!-- Comprobante -->
                @if($payment->receipt_path)
                <div class="text-center">
                    @php
                    $isPdf = Str::endsWith(strtolower($payment->receipt_path), '.pdf');
                    @endphp
                    @if($isPdf)
                    <div class="flex flex-col items-center justify-center cursor-pointer"
                        onclick="window.open('{{ asset('storage/' . $payment->receipt_path) }}', '_blank')">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-red-50 rounded-lg border border-gray-200 hover:scale-105 transition-transform">
                            <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">PDF</p>
                    </div>
                    @else
                    <img src="{{ asset('storage/' . $payment->receipt_path) }}" alt="Comprobante"
                        class="mx-auto rounded-lg shadow-md cursor-pointer max-h-32 border border-gray-200 hover:scale-105 transition-transform"
                        onclick="viewReceipt({{ $payment->id }}, '{{ asset('storage/' . $payment->receipt_path) }}')">
                    <p class="text-xs text-gray-500 mt-1">Click para ampliar</p>
                    @endif
                </div>
                @endif

                <!-- Acciones -->
                <div class="flex flex-wrap gap-1 pt-2 border-t border-gray-100">
                    @if($payment->receipt_path)
                    <button type="button"
                        class="flex-1 min-w-0 text-center px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye text-xs"></i>
                    </button>
                    @endif

                    @if($payment->status == 'pending_verification')
                    <button type="button"
                        class="px-2 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors"
                        onclick="updatePaymentStatus({{ $payment->id }}, 'approved')">
                        <i class="fas fa-check text-xs"></i>
                    </button>
                    <button type="button"
                        class="px-2 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition-colors"
                        onclick="quickReject({{ $payment->id }})">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    @endif

                    <a href="{{ route('admin.orders.index', ['search' => $payment->user->email]) }}"
                        class="px-2 py-1 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700 transition-colors"
                        target="_blank">
                        <i class="fas fa-shopping-cart text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Vista de Tabla -->
<div id="table-view" class="view-content hidden p-3 sm:p-6">
    @if($payments->isEmpty())
    <div class="text-center py-12">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-4">
            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">¡Excelente!</h3>
        <p class="text-gray-500">No hay comprobantes pendientes de verificación.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Usuario
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Pago
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Orden
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Estado
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Comprobante
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-4 whitespace-nowrap sm:px-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-xs">{{ $payment->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap sm:px-6">
                        <div class="text-sm font-semibold text-green-600">${{ number_format($payment->amount, 2) }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $payment->payment_method == 'bank_transfer' ?
                            'Transferencia' : 'QR PayPhone' }}</div>
                        <div class="text-xs text-gray-400">{{ $payment->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap sm:px-6">
                        <div class="text-sm font-bold text-blue-600">#{{ $payment->order->id ?? 'N/A' }}</div>
                        @if($payment->order)
                        <div class="text-xs text-gray-500">{{ $payment->order->created_at->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap sm:px-6">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            {{ $payment->status == 'approved' ? 'bg-green-100 text-green-800' : 
                               ($payment->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $payment->status == 'approved' ? '✅ Aprobado' :
                            ($payment->status == 'rejected' ? '❌ Rechazado' : '🟡 Pendiente') }}
                        </span>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-center sm:px-6">
                        @if($payment->receipt_path)
                        @php
                        $isPdf = Str::endsWith(strtolower($payment->receipt_path), '.pdf');
                        @endphp
                        @if($isPdf)
                        <div class="flex justify-center">
                            <div class="cursor-pointer"
                                onclick="window.open('{{ asset('storage/' . $payment->receipt_path) }}', '_blank')">
                                <i class="fas fa-file-pdf text-red-500 text-lg hover:text-red-600"></i>
                                <p class="text-xs text-gray-500">PDF</p>
                            </div>
                        </div>
                        @else
                        <img src="{{ asset('storage/' . $payment->receipt_path) }}" alt="Comprobante"
                            class="mx-auto w-16 h-16 object-cover rounded cursor-pointer border border-gray-200 hover:scale-110 transition-transform"
                            onclick="viewReceipt({{ $payment->id }}, '{{ asset('storage/' . $payment->receipt_path) }}')">
                        @endif
                        @else
                        <span class="text-xs text-gray-400">Sin comprobante</span>
                        @endif
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium space-x-1 sm:px-6">
                        @if($payment->receipt_path)
                        <button type="button"
                            class="inline-flex items-center px-1.5 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700"
                            onclick="viewReceipt({{ $payment->id }}, '{{ asset('storage/' . $payment->receipt_path) }}')">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                        @endif

                        @if($payment->status == 'pending_verification')
                        <button type="button"
                            class="inline-flex items-center px-1.5 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700"
                            onclick="updatePaymentStatus({{ $payment->id }}, 'approved')">
                            <i class="fas fa-check text-xs"></i>
                        </button>
                        <button type="button"
                            class="inline-flex items-center px-1.5 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                            onclick="quickReject({{ $payment->id }})">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                        @endif

                        <a href="{{ route('admin.orders.index', ['search' => $payment->user->email]) }}"
                            class="inline-flex items-center px-1.5 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700"
                            target="_blank">
                            <i class="fas fa-shopping-cart text-xs"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Paginación -->
@if($payments->hasPages())
<div class="px-3 py-4 sm:px-6">
    <div class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
            @if ($payments->onFirstPage())
            <span
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                Anterior
            </span>
            @else
            <a href="{{ $payments->previousPageUrl() }}"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Anterior
            </a>
            @endif

            @if ($payments->hasMorePages())
            <a href="{{ $payments->nextPageUrl() }}"
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Siguiente
            </a>
            @else
            <span
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                Siguiente
            </span>
            @endif
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ $payments->firstItem() ?? 0 }}</span>
                    a
                    <span class="font-medium">{{ $payments->lastItem() ?? 0 }}</span>
                    de
                    <span class="font-medium">{{ $payments->total() }}</span>
                    resultados
                </p>
            </div>
            <div>
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endif

<script>
    function quickReject(paymentId) {
        currentPaymentId = paymentId;
        Swal.fire({
            title: 'Rechazar Pago',
            input: 'textarea',
            inputLabel: 'Motivo del rechazo',
            inputPlaceholder: 'Explica por qué se rechaza este comprobante...',
            inputAttributes: {
                'aria-label': 'Motivo del rechazo'
            },
            showCancelButton: true,
            confirmButtonText: 'Rechazar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#EF4444',
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes especificar un motivo del rechazo'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updatePaymentStatus(paymentId, 'rejected', result.value);
            }
        });
    }
</script>