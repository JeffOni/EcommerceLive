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
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-primary-200/30 to-secondary-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-secondary-200/30 to-primary-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-64 h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-primary-100/40 to-secondary-100/40 blur-2xl">
            </div>
        </div>

        <div class="relative">
            <!-- Contenedor principal responsive con backdrop blur -->
            <div
                class="mx-2 my-4 overflow-hidden shadow-lg sm:mx-4 sm:my-8 sm:shadow-2xl glass-effect rounded-xl sm:rounded-3xl">
                <!-- Header responsive con gradiente -->
                <div
                    class="px-4 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6 bg-gradient-to-r from-primary-600 to-secondary-600">
                    <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <div class="flex items-center flex-1 min-w-0 space-x-2 sm:space-x-3">
                            <div class="flex-shrink-0 p-2 rounded-lg sm:p-3 glass-effect sm:rounded-xl">
                                <i class="text-lg text-white sm:text-xl fas fa-receipt"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-lg font-bold text-white truncate sm:text-xl lg:text-2xl">Verificación de
                                    Pagos</h2>
                                <p class="text-xs truncate sm:text-sm text-secondary-100">Revisa y verifica los
                                    comprobantes de pago</p>
                            </div>
                        </div>
                        <div class="flex items-center flex-shrink-0 text-xs sm:text-sm text-white/80">
                            <i class="mr-1 fas fa-clock"></i>
                            {{ $payments->total() ?? $payments->count() }} comprobantes
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas con filtros -->
                <div class="px-3 py-3 bg-white border-b border-gray-200 sm:px-6 sm:py-4 lg:px-8">
                    <div class="flex flex-col space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
                        <!-- Controles de vista responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <span class="flex-shrink-0 text-sm font-medium text-gray-700">Vista:</span>
                            <div class="flex w-full p-1 bg-gray-100 rounded-lg sm:w-auto">
                                <button onclick="toggleView('cards')" id="cards-btn"
                                    class="flex-1 px-3 py-2 text-xs font-medium text-white transition-all duration-200 rounded-md shadow-sm sm:flex-none sm:px-4 sm:text-sm bg-primary-600 view-toggle">
                                    <i class="mr-1 text-xs sm:mr-2 fas fa-th-large sm:text-sm"></i>
                                    <span class="hidden sm:inline">Tarjetas</span>
                                    <span class="sm:hidden">Cards</span>
                                </button>
                                <button onclick="toggleView('table')" id="table-btn"
                                    class="flex-1 px-3 py-2 text-xs font-medium text-gray-600 transition-all duration-200 rounded-md sm:flex-none sm:px-4 sm:text-sm view-toggle hover:text-gray-900">
                                    <i class="mr-1 text-xs sm:mr-2 fas fa-table sm:text-sm"></i>
                                    <span class="hidden sm:inline">Tabla</span>
                                    <span class="sm:hidden">Table</span>
                                </button>
                            </div>
                        </div>

                        <!-- Filtros por método y status -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <span class="flex-shrink-0 text-sm font-medium text-gray-700">Filtrar por:</span>
                            <select id="status-filter"
                                class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg sm:w-auto sm:text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="pending_verification" {{ request('status', 'pending_verification'
                                    )=='pending_verification' ? 'selected' : '' }}>
                                    🟡 Pendientes
                                </option>
                                <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>
                                    ✅ Aprobados
                                </option>
                                <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>
                                    ❌ Rechazados
                                </option>
                                <option value="all" {{ request('status')=='all' ? 'selected' : '' }}>
                                    📋 Todos
                                </option>
                            </select>
                            <select id="method-filter"
                                class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg sm:w-auto sm:text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Todos los métodos</option>
                                <option value="bank_transfer" {{ request('method')=='bank_transfer' ? 'selected' : ''
                                    }}>Transferencia</option>
                                <option value="payphone" {{ request('method')=='payphone' ? 'selected' : '' }}>
                                    QR PayPhone</option>
                            </select>
                        </div>

                        <!-- Acciones y configuración responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <button onclick="refreshStats()"
                                class="w-full px-4 py-2 text-xs text-gray-700 transition-colors bg-gray-100 rounded-lg sm:w-auto sm:text-sm hover:bg-gray-200">
                                <i class="mr-1 fas fa-sync-alt"></i><span class="hidden sm:inline"> Actualizar</span>
                            </button>
                            <select id="items-per-page"
                                class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg sm:w-auto sm:text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="12" {{ request('per_page')=='12' ? 'selected' : '' }}>12 por página
                                </option>
                                <option value="24" {{ request('per_page')=='24' ? 'selected' : '' }}>24 por página
                                </option>
                                <option value="48" {{ request('per_page')=='48' ? 'selected' : '' }}>48 por página
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
        let currentView = 'cards';
        let isLoading = false;

        // Función para cambiar entre vistas
        function toggleView(viewType) {
            // Actualizar la variable global
            currentView = viewType;
            
            // Ocultar todas las vistas
            document.querySelectorAll('.view-content').forEach(view => {
                view.classList.add('hidden');
            });

            // Mostrar la vista seleccionada
            const targetView = document.getElementById(viewType + '-view');
            if (targetView) {
                targetView.classList.remove('hidden');
            }

            // Actualizar botones
            document.querySelectorAll('.view-toggle').forEach(btn => {
                btn.classList.remove('bg-primary-600', 'text-white', 'shadow-sm');
                btn.classList.add('text-gray-600', 'hover:text-gray-900');
            });

            const selectedBtn = document.getElementById(viewType + '-btn');
            if (selectedBtn) {
                selectedBtn.classList.add('bg-primary-600', 'text-white', 'shadow-sm');
                selectedBtn.classList.remove('text-gray-600', 'hover:text-gray-900');
            }

            // Guardar preferencia
            localStorage.setItem('admin_payments_view', viewType);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Restaurar vista guardada
            const savedView = localStorage.getItem('admin_payments_view') || 'cards';
            if (savedView !== 'cards') {
                toggleView(savedView);
            }

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
                if (isLoading) return;
                
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
                if (isLoading) return;
                
                isLoading = true;
                const container = document.getElementById('payments-content');
                if (container) {
                    container.style.opacity = '0.6';
                }

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        if (container) {
                            // Extraer solo el contenido del contenedor
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector('#payments-content');
                            
                            if (newContent) {
                                container.innerHTML = newContent.innerHTML;
                            } else {
                                container.innerHTML = html;
                            }

                            // Restaurar vista seleccionada
                            const savedView = localStorage.getItem('admin_payments_view') || 'cards';
                            setTimeout(() => toggleView(savedView), 100);
                            
                            container.style.opacity = '1';
                        }
                        isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (container) {
                            container.style.opacity = '1';
                        }
                        isLoading = false;
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al cargar los comprobantes',
                                icon: 'error'
                            });
                        } else {
                            alert('Error al cargar los comprobantes');
                        }
                    });
            }

            function viewReceipt(paymentId, receiptPath) {
                currentPaymentId = paymentId;

                // Detectar si es PDF
                const isPdf = receiptPath.toLowerCase().endsWith('.pdf');
                let htmlContent = '';
                if (isPdf) {
                    htmlContent = `
                        <div class="text-center">
                            <iframe src="${receiptPath}" style="width:100%;height:70vh;border-radius:12px;border:1px solid #ddd;background:#fff;"></iframe>
                            <p class="mt-2 text-xs text-gray-500">Archivo PDF - Puedes descargarlo o imprimirlo desde el visor</p>
                        </div>
                    `;
                } else {
                    htmlContent = `
                        <div class="text-center">
                            <img src="${receiptPath}" alt="Comprobante" style="max-width: 100%; max-height: 70vh;" class="rounded">
                        </div>
                    `;
                }
                Swal.fire({
                    title: 'Comprobante de Pago',
                    html: htmlContent,
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
            @apply bg-green-100 text-green-700 hover: bg-green-200;
        }

        .btn-reject {
            @apply bg-red-100 text-red-700 hover: bg-red-200;
        }

        .btn-view {
            @apply bg-secondary-100 text-secondary-700 hover: bg-secondary-200;
        }
    </style>
    @endpush

</x-admin-layout>