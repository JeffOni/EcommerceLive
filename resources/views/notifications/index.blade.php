@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
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
        </div>

        <div class="relative px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Centro de Notificaciones</h1>
                        <p class="mt-2 text-gray-600">Mantente al día con las actualizaciones de tus pedidos</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if ($notifications->total() > 0)
                            <button id="markAllRead"
                                class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="mr-2 fas fa-check-double"></i>
                                Marcar todas como leídas
                            </button>
                        @endif
                        <span class="text-sm text-gray-500">
                            {{ $notifications->total() }} notificaciones totales
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notificaciones -->
            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="p-6 transition-all duration-200 bg-white border border-gray-200 rounded-xl hover:shadow-md {{ $notification->read_at ? 'opacity-75' : 'ring-2 ring-blue-100' }}"
                        data-notification-id="{{ $notification->id }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Icono y título -->
                                <div class="flex items-center mb-3">
                                    @php
                                        $iconClass = 'fas fa-bell';
                                        $iconColor = 'text-blue-500';

                                        if (isset($notification->data['order_id'])) {
                                            $iconClass = 'fas fa-shopping-cart';
                                            $iconColor = 'text-green-500';
                                        }
                                        if (isset($notification->data['payment_id'])) {
                                            $iconClass = 'fas fa-credit-card';
                                            $iconColor =
                                                isset($notification->data['status']) &&
                                                $notification->data['status'] === 'approved'
                                                    ? 'text-green-500'
                                                    : 'text-red-500';
                                        }
                                    @endphp

                                    <div class="flex items-center justify-center w-10 h-10 mr-4 rounded-full bg-gray-50">
                                        <i class="{{ $iconClass }} {{ $iconColor }}"></i>
                                    </div>

                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">
                                            @if (isset($notification->data['order_id']))
                                                Actualización de Pedido #{{ $notification->data['order_id'] }}
                                            @elseif(isset($notification->data['payment_id']))
                                                Verificación de Pago
                                            @else
                                                Notificación
                                            @endif
                                        </h3>

                                        @if (!$notification->read_at)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">
                                                Nueva
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Mensaje -->
                                <p class="mb-3 text-gray-700">
                                    {{ $notification->data['message'] ?? 'Tienes una nueva notificación' }}
                                </p>

                                <!-- Detalles adicionales -->
                                @if (isset($notification->data['old_status']) && isset($notification->data['new_status']))
                                    <div class="flex items-center p-3 mb-3 rounded-lg bg-gray-50">
                                        <span class="text-sm text-gray-600">
                                            Estado:
                                            <span
                                                class="font-medium text-gray-800">{{ ucfirst($notification->data['old_status']) }}</span>
                                            <i class="mx-2 text-gray-400 fas fa-arrow-right"></i>
                                            <span
                                                class="font-medium text-green-600">{{ ucfirst($notification->data['new_status']) }}</span>
                                        </span>
                                    </div>
                                @endif

                                <!-- Fecha -->
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="mr-2 fas fa-clock"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                    <span class="mx-2">•</span>
                                    {{ $notification->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex items-center ml-4 space-x-2">
                                @if (isset($notification->data['tracking_url']))
                                    <a href="{{ $notification->data['tracking_url'] }}"
                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                                        <i class="mr-1 fas fa-eye"></i>
                                        Ver Detalles
                                    </a>
                                @endif

                                @if (!$notification->read_at)
                                    <button onclick="markAsRead('{{ $notification->id }}')"
                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                        <i class="mr-1 fas fa-check"></i>
                                        Marcar como leída
                                    </button>
                                @endif

                                <button onclick="deleteNotification('{{ $notification->id }}')"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                            <i class="text-6xl fas fa-bell-slash"></i>
                        </div>
                        <h3 class="mb-2 text-lg font-medium text-gray-900">No tienes notificaciones</h3>
                        <p class="text-gray-500">Cuando tengas actualizaciones en tus pedidos aparecerán aquí</p>
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if ($notifications->hasPages())
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function markAsRead(notificationId) {
                fetch(`/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
                            notification.classList.remove('ring-2', 'ring-blue-100');
                            notification.classList.add('opacity-75');

                            // Remover botón de marcar como leída
                            const markBtn = notification.querySelector('button[onclick*="markAsRead"]');
                            if (markBtn) markBtn.remove();

                            // Remover badge "Nueva"
                            const newBadge = notification.querySelector('.bg-blue-100');
                            if (newBadge && newBadge.textContent.trim() === 'Nueva') {
                                newBadge.remove();
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            function deleteNotification(notificationId) {
                if (confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
                    fetch(`/notifications/${notificationId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
                                notification.remove();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            }

            document.getElementById('markAllRead')?.addEventListener('click', function() {
                fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        </script>
    @endpush
@endsection
