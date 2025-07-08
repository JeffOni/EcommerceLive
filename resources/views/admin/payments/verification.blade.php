<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Verificación de Pagos',
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-blue-200/30 to-purple-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-200/30 to-blue-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-64 h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-blue-100/40 to-purple-100/40 blur-2xl">
            </div>
        </div>

        <div class="relative">
            <!-- Contenedor principal con backdrop blur -->
            <div class="mx-4 my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header con gradiente -->
                <div class="px-8 py-6 bg-gradient-to-r from-purple-600 to-blue-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 glass-effect rounded-xl">
                                <i class="text-xl text-white fas fa-receipt"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Verificación de Pagos</h2>
                                <p class="text-sm text-purple-100">Revisa y verifica los comprobantes de pago</p>
                            </div>
                        </div>
                        <div class="text-sm text-white/80">
                            <i class="mr-1 fas fa-clock"></i>
                            {{ $payments->total() ?? $payments->count() }} comprobantes pendientes
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas con filtros -->
                <div class="px-8 py-4 bg-white border-b border-gray-200">
                    <div
                        class="flex flex-col items-start justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
                        <!-- Filtros por método y status -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">Filtrar por:</span>
                            <select id="status-filter"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="pending_verification"
                                    {{ request('status', 'pending_verification') == 'pending_verification' ? 'selected' : '' }}>
                                    🟡 Pendientes
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    ✅ Aprobados
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    ❌ Rechazados
                                </option>
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>
                                    📋 Todos
                                </option>
                            </select>
                            <select id="method-filter"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Todos los métodos</option>
                                <option value="bank_transfer"
                                    {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>
                                    Transferencia Bancaria
                                </option>
                                <option value="payphone" {{ request('method') == 'payphone' ? 'selected' : '' }}>
                                    QR PayPhone
                                </option>
                            </select>
                        </div>

                        <!-- Acciones rápidas -->
                        <div class="flex items-center space-x-4">
                            <button onclick="refreshStats()"
                                class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-sync-alt mr-1"></i> Actualizar
                            </button>
                            <select id="items-per-page"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="12" {{ request('per_page') == '12' ? 'selected' : '' }}>12 por página
                                </option>
                                <option value="24" {{ request('per_page') == '24' ? 'selected' : '' }}>24 por página
                                </option>
                                <option value="48" {{ request('per_page') == '48' ? 'selected' : '' }}>48 por página
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="overflow-hidden bg-white" id="payments-content">
                    @include('admin.payments.partials.verification-content')
                </div>
            </div>
        </div>
    </div>

    <!-- Eliminados modales Bootstrap innecesarios. Todo el flujo de revisión y rechazo usa SweetAlert2 -->
    @push('js')
        <script>
            let currentPaymentId = null;

            document.addEventListener('DOMContentLoaded', function() {
                // Configurar filtros
                const statusFilter = document.getElementById('status-filter');
                const methodFilter = document.getElementById('method-filter');
                const itemsPerPage = document.getElementById('items-per-page');

                if (statusFilter) {
                    statusFilter.addEventListener('change', filterPayments);
                }

                if (methodFilter) {
                    methodFilter.addEventListener('change', filterPayments);
                }

                if (itemsPerPage) {
                    itemsPerPage.addEventListener('change', filterPayments);
                }

                // Manejar paginación AJAX
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.pagination a')) {
                        e.preventDefault();
                        const url = e.target.closest('.pagination a').href;
                        loadPaymentsPage(url);
                    }
                });
            });

            function filterPayments() {
                const status = document.getElementById('status-filter').value;
                const method = document.getElementById('method-filter').value;
                const perPage = document.getElementById('items-per-page').value;
                const params = new URLSearchParams();

                if (status) params.append('status', status);
                if (method) params.append('method', method);
                if (perPage) params.append('per_page', perPage);

                const url = `{{ route('admin.payments.verification') }}?${params.toString()}`;
                loadPaymentsPage(url);

                // Actualizar URL del navegador
                window.history.pushState({}, '', url);
            }

            function loadPaymentsPage(url) {
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('payments-content').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al cargar los comprobantes',
                            icon: 'error'
                        });
                    });
            }

            function viewReceipt(paymentId, receiptPath) {
                currentPaymentId = paymentId;

                Swal.fire({
                    title: 'Comprobante de Pago',
                    html: `
                        <div class="text-center">
                            <img src="${receiptPath}" alt="Comprobante" style="max-width: 100%; max-height: 70vh;" class="rounded">
                        </div>
                    `,
                    width: '80%',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: '<i class="fas fa-check"></i> Aprobar',
                    denyButtonText: '<i class="fas fa-times"></i> Rechazar',
                    cancelButtonText: 'Cerrar',
                    confirmButtonColor: '#10B981',
                    denyButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        approvePayment();
                    } else if (result.isDenied) {
                        rejectPayment();
                    }
                });
            }

            function approvePayment() {
                if (!currentPaymentId) return;

                Swal.fire({
                    title: '¿Aprobar Pago?',
                    text: 'Al aprobar este pago, la orden pasará a estado "Pago Verificado"',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Sí, Aprobar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updatePaymentStatus(currentPaymentId, 'approved');
                    }
                });
            }

            function rejectPayment() {
                if (!currentPaymentId) return;

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
                        updatePaymentStatus(currentPaymentId, 'rejected', result.value);
                    }
                });
            }

            function quickReject(paymentId) {
                currentPaymentId = paymentId;
                rejectPayment();
            }

            function updatePaymentStatus(paymentId, status, reason = null) {
                const data = {
                    status: status
                };
                if (reason) data.reason = reason;

                fetch(`/admin/payments/${paymentId}/verify`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: data.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            filterPayments(); // Recargar tabla
                        } else {
                            throw new Error(data.message || 'Error al actualizar');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al procesar la verificación',
                            icon: 'error'
                        });
                    });
            }

            function refreshStats() {
                filterPayments();
            }
        </script>
    @endpush

    @push('css')
        <style>
            .glass-effect {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .payment-card {
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                margin-bottom: 1rem;
                transition: all 0.3s ease;
                background: white;
            }

            .payment-card:hover {
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                transform: translateY(-2px);
            }

            .receipt-preview {
                max-width: 100px;
                max-height: 100px;
                object-fit: cover;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: transform 0.2s ease;
                border: 2px solid #e5e7eb;
            }

            .receipt-preview:hover {
                transform: scale(1.05);
                border-color: #8b5cf6;
            }

            .payment-method-badge {
                font-size: 0.75rem;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-weight: 500;
            }

            .amount-display {
                font-size: 1.5rem;
                font-weight: bold;
                color: #10b981;
            }

            .action-btn {
                @apply px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200;
            }

            .btn-approve {
                @apply bg-green-100 text-green-700 hover:bg-green-200;
            }

            .btn-reject {
                @apply bg-red-100 text-red-700 hover:bg-red-200;
            }

            .btn-view {
                @apply bg-blue-100 text-blue-700 hover:bg-blue-200;
            }
        </style>
    @endpush

</x-admin-layout>
