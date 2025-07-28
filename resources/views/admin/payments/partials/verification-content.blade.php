<!-- Vista de Tarjetas -->
<div id="cards-view" class="p-3 view-content sm:p-6">
    @if($payments->isEmpty())
    <div class="py-12 text-center">
        <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full">
            <i class="text-2xl text-green-600 fas fa-check-circle"></i>
        </div>
        <h3 class="mb-2 text-lg font-medium text-gray-900">¡Excelente!</h3>
        <p class="text-gray-500">No hay comprobantes pendientes de verificación.</p>
        <a href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center px-4 py-2 mt-4 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
            <i class="mr-2 fas fa-shopping-cart"></i> Ver Órdenes
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 sm:gap-6">
        @foreach($payments as $payment)
        <div class="overflow-hidden transition-shadow duration-300 bg-white rounded-lg shadow-md hover:shadow-lg">
            <!-- Header de la tarjeta -->
            <div class="px-4 py-3 bg-gradient-to-r from-primary-600 to-secondary-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="text-sm text-white fas fa-receipt"></i>
                        <span class="text-sm font-semibold text-white">${{ number_format($payment->amount, 2) }}</span>
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
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                            <i class="text-xs text-blue-600 fas fa-user"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
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
                        <span class="text-xs font-medium text-blue-600">#{{ $payment->order->id ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="text-xs text-gray-500">
                    <i class="mr-1 fas fa-calendar"></i>{{ $payment->created_at->format('d/m/Y H:i') }}
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
                            class="flex items-center justify-center w-16 h-16 transition-transform border border-gray-200 rounded-lg bg-red-50 hover:scale-105">
                            <i class="text-2xl text-red-500 fas fa-file-pdf"></i>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">PDF</p>
                    </div>
                    @else
                    <img src="{{ asset('storage/' . $payment->receipt_path) }}" alt="Comprobante"
                        class="mx-auto transition-transform border border-gray-200 rounded-lg shadow-md cursor-pointer max-h-32 hover:scale-105"
                        onclick="viewReceipt({{ $payment->id }}, '{{ asset('storage/' . $payment->receipt_path) }}')">
                    <p class="mt-1 text-xs text-gray-500">Click para ampliar</p>
                    @endif
                </div>
                @endif

                <!-- Acciones -->
                <div class="flex flex-wrap gap-1 pt-2 border-t border-gray-100">
                    @if($payment->receipt_path)
                    <button type="button"
                        class="flex-1 min-w-0 px-2 py-1 text-xs font-medium text-center text-white transition-colors bg-blue-600 rounded hover:bg-blue-700">
                        <i class="text-xs fas fa-eye"></i>
                    </button>
                    @endif

                    @if($payment->status == 'pending_verification')
                    <button type="button"
                        class="px-2 py-1 text-xs font-medium text-white transition-colors bg-green-600 rounded hover:bg-green-700"
                        onclick="updatePaymentStatus({{ $payment->id }}, 'approved')">
                        <i class="text-xs fas fa-check"></i>
                    </button>
                    <button type="button"
                        class="px-2 py-1 text-xs font-medium text-white transition-colors bg-red-600 rounded hover:bg-red-700"
                        onclick="quickReject({{ $payment->id }})">
                        <i class="text-xs fas fa-times"></i>
                    </button>
                    @endif

                    <a href="{{ route('admin.orders.index', ['search' => $payment->user->email]) }}"
                        class="px-2 py-1 text-xs font-medium text-white transition-colors bg-gray-600 rounded hover:bg-gray-700"
                        target="_blank">
                        <i class="text-xs fas fa-shopping-cart"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Vista de Tabla -->
<div id="table-view" class="hidden p-3 view-content sm:p-6">
    @if($payments->isEmpty())
    <div class="py-12 text-center">
        <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full">
            <i class="text-2xl text-green-600 fas fa-check-circle"></i>
        </div>
        <h3 class="mb-2 text-lg font-medium text-gray-900">¡Excelente!</h3>
        <p class="text-gray-500">No hay comprobantes pendientes de verificación.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6">
                        Usuario
                    </th>
                    <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6">
                        Pago
                    </th>
                    <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6">
                        Orden
                    </th>
                    <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6">
                        Estado
                    </th>
                    <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6">
                        Comprobante
                    </th>
                    <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-4 whitespace-nowrap sm:px-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                    <i class="text-xs text-blue-600 fas fa-user"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                <div class="max-w-xs text-xs text-gray-500 truncate">{{ $payment->user->email }}</div>
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
                    <td class="px-3 py-4 text-center whitespace-nowrap sm:px-6">
                        @if($payment->receipt_path)
                        @php
                        $isPdf = Str::endsWith(strtolower($payment->receipt_path), '.pdf');
                        @endphp
                        @if($isPdf)
                        <div class="flex justify-center">
                            <div class="cursor-pointer"
                                onclick="window.open('{{ asset('storage/' . $payment->receipt_path) }}', '_blank')">
                                <i class="text-lg text-red-500 fas fa-file-pdf hover:text-red-600"></i>
                                <p class="text-xs text-gray-500">PDF</p>
                            </div>
                        </div>
                        @else
                        <img src="{{ asset('storage/' . $payment->receipt_path) }}" alt="Comprobante"
                            class="object-cover w-16 h-16 mx-auto transition-transform border border-gray-200 rounded cursor-pointer hover:scale-110"
                            onclick="viewReceipt({{ $payment->id }}, '{{ asset('storage/' . $payment->receipt_path) }}')">
                        @endif
                        @else
                        <span class="text-xs text-gray-400">Sin comprobante</span>
                        @endif
                    </td>
                    <td class="px-3 py-4 space-x-1 text-sm font-medium whitespace-nowrap sm:px-6">
                        @if($payment->receipt_path)
                        <button type="button"
                            class="inline-flex items-center px-1.5 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700"
                            onclick="viewReceipt({{ $payment->id }}, '{{ asset('storage/' . $payment->receipt_path) }}')">
                            <i class="text-xs fas fa-eye"></i>
                        </button>
                        @endif

                        @if($payment->status == 'pending_verification')
                        <button type="button"
                            class="inline-flex items-center px-1.5 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700"
                            onclick="updatePaymentStatus({{ $payment->id }}, 'approved')">
                            <i class="text-xs fas fa-check"></i>
                        </button>
                        <button type="button"
                            class="inline-flex items-center px-1.5 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                            onclick="quickReject({{ $payment->id }})">
                            <i class="text-xs fas fa-times"></i>
                        </button>
                        @endif

                        <a href="{{ route('admin.orders.index', ['search' => $payment->user->email]) }}"
                            class="inline-flex items-center px-1.5 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700"
                            target="_blank">
                            <i class="text-xs fas fa-shopping-cart"></i>
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
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($payments->onFirstPage())
            <span
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md cursor-default">
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
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md cursor-default">
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