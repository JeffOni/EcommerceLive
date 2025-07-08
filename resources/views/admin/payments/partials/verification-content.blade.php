@if ($payments->count() > 0)
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($payments as $payment)
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h6 class="font-semibold text-gray-900 mb-1 flex items-center">
                                    <i class="fas fa-user mr-2 text-gray-500"></i>{{ $payment->user->name }}
                                </h6>
                                <p class="text-xs text-gray-500">{{ $payment->user->email }}</p>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold {{ $payment->payment_method == 'bank_transfer' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ $payment->payment_method == 'bank_transfer' ? 'Transferencia' : ($payment->payment_method == 'qr_deuna' ? 'QR DeUna' : 'QR PayPhone') }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <span
                                class="text-2xl font-bold text-gray-900">${{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="mb-2 flex items-center text-xs text-gray-500">
                            <i class="fas fa-calendar mr-1"></i> {{ $payment->created_at->format('d/m/Y H:i') }}
                        </div>
                        @if ($payment->transaction_id)
                            <div class="mb-1 text-xs text-gray-600">
                                <span class="font-medium">ID Transacción:</span> {{ $payment->transaction_id }}
                            </div>
                        @endif
                        @if ($payment->transaction_number)
                            <div class="mb-1 text-xs text-gray-600">
                                <span class="font-medium">Número:</span> {{ $payment->transaction_number }}
                            </div>
                        @endif
                        @if ($payment->comments)
                            <div class="mb-2 text-xs text-gray-600">
                                <span class="font-medium">Comentarios:</span> {{ Str::limit($payment->comments, 100) }}
                            </div>
                        @endif
                        @if ($payment->receipt_path)
                            <div class="text-center my-4">
                                <img src="{{ asset('storage/' . $payment->receipt_path) }}" alt="Comprobante"
                                    class="mx-auto rounded-lg shadow-md cursor-pointer max-h-48 border border-gray-200 hover:scale-105 transition-transform"
                                    onclick="viewReceipt({{ $payment->id }}, '{{ asset('storage/' . $payment->receipt_path) }}')">
                                <p class="text-xs text-gray-500 mt-2">Click para ampliar</p>
                            </div>
                        @else
                            <div class="text-center my-4">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                    <p class="text-xs text-yellow-700 mt-1">Sin comprobante adjunto</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-2 mt-4">
                        @if ($payment->receipt_path)
                            <button type="button"
                                class="flex-1 px-3 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors text-sm"
                                onclick="viewReceipt({{ $payment->id }}, '{{ asset('storage/' . $payment->receipt_path) }}')">
                                <i class="fas fa-eye mr-1"></i> Revisar
                            </button>
                        @endif
                        <button type="button"
                            class="flex-1 px-3 py-2 rounded-lg bg-red-100 text-red-700 font-semibold hover:bg-red-200 transition-colors text-sm"
                            onclick="quickReject({{ $payment->id }})">
                            <i class="fas fa-times mr-1"></i> Rechazar
                        </button>
                        <a href="{{ route('admin.orders.index', ['search' => $payment->user->email]) }}"
                            class="flex-1 px-3 py-2 rounded-lg bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-colors text-sm text-center"
                            target="_blank">
                            <i class="fas fa-shopping-cart mr-1"></i> Órdenes
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-center mt-8">
            {{ $payments->links() }}
        </div>
    </div>
@else
    <div class="text-center py-16">
        <div class="mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-check-circle text-3xl text-green-600"></i>
        </div>
        <h4 class="text-xl font-semibold text-gray-900 mb-2">¡Excelente!</h4>
        <p class="text-gray-600 mb-6">No hay comprobantes pendientes de verificación</p>
        <a href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-shopping-cart mr-2"></i> Ver Órdenes
        </a>
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
