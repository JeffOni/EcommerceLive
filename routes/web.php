<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Gate;


// Rutas administrativas para verificación de pagos
Route::middleware(['auth', 'can:admin-panel'])->prefix('admin')->group(function () {
    Route::get('/payments/verification', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'index'])->name('admin.payments.verification');
    Route::patch('/payments/{payment}/verify', [\App\Http\Controllers\Admin\PaymentVerificationController::class, 'verify'])->name('admin.payments.verify');

    // Rutas para gestión de ofertas
    Route::prefix('offers')->name('admin.offers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OfferController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\OfferController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\OfferController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [\App\Http\Controllers\Admin\OfferController::class, 'edit'])->name('edit');
        Route::put('/{product}', [\App\Http\Controllers\Admin\OfferController::class, 'update'])->name('update');
        Route::delete('/{product}', [\App\Http\Controllers\Admin\OfferController::class, 'destroy'])->name('destroy');
        Route::post('/clean-expired', [\App\Http\Controllers\Admin\OfferController::class, 'cleanExpired'])->name('clean-expired');
    });
});


Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');

// RUTA TEMPORAL DE DEBUG - ELIMINAR DESPUÉS DE SOLUCIONAR
Route::get('/debug-auth', function () {
    $user = auth()->user();

    $debug = [
        'authenticated' => auth()->check(),
        'user_id' => $user ? $user->id : null,
        'user_name' => $user ? $user->name : null,
        'user_email' => $user ? $user->email : null,
        'user_roles' => $user ? $user->roles->pluck('name')->toArray() : [],
        'has_admin_role' => $user ? $user->hasRole('admin') : false,
        'has_super_admin_role' => $user ? $user->hasRole('super_admin') : false,
        'has_any_admin_role' => $user ? $user->hasAnyRole(['admin', 'super_admin']) : false,
        'can_access_admin_panel' => auth()->check() ? Gate::allows('admin-panel') : false,
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token(),
        'dashboard_redirect_logic' => $user && $user->hasAnyRole(['admin', 'super_admin']) ? 'Should redirect to /admin' : 'Should redirect to welcome',
        'current_route' => request()->route() ? request()->route()->getName() : 'No route'
    ];

    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
})->middleware('web');

// DEBUG: Ruta para probar directamente acceso admin con middleware
Route::get('/debug-admin-middleware', function () {
    return response()->json([
        'success' => true,
        'message' => '✅ MIDDLEWARE ADMIN FUNCIONANDO',
        'user' => auth()->user()->name,
        'roles' => auth()->user()->roles->pluck('name')->toArray(),
        'timestamp' => now()->format('Y-m-d H:i:s'),
        'can_access_admin' => true
    ]);
})->middleware(['web', 'auth', 'can:admin-panel']);

// DEBUG: Ruta para probar directamente el dashboard logic
Route::get('/debug-dashboard', function () {
    $user = auth()->user();
    if ($user && $user->hasAnyRole(['admin', 'super_admin'])) {
        return redirect('/admin');
    }
    // Si es cliente, redirigir a la página principal
    return redirect()->route('welcome.index');
})->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->name('debug.dashboard');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->hasAnyRole(['admin', 'super_admin'])) {
            return redirect('/admin');
        }
        // Si es cliente, redirigir a la página principal
        return redirect()->route('welcome.index');
    })->name('dashboard');

    // Rutas protegidas que requieren autenticación
    Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');

    // Ruta para eliminar direcciones - Implementa el patrón admin con SweetAlert
    // Protegida por middleware de autenticación y verificación
    // Se conecta con el componente Livewire ShippingAddresses para confirmar eliminación
    Route::delete('/addresses/{address}', [ShippingController::class, 'destroy'])->name('addresses.destroy');


});

// El método scopeBindings() en la ruta aplica el enlace automático de modelos anidados en rutas.
// Por ejemplo, si tienes una ruta como /families/{family}/products/{product},
// scopeBindings() asegura que el modelo {product} se busque solo entre los productos de la familia {family},
// evitando acceder a productos que no pertenezcan a esa familia.
Route::get('/families/{family}', [FamilyController::class, 'show'])
    ->name('families.show')
    ->scopeBindings();

Route::get('/categories/{category}', [CategoryController::class, 'show'])
    ->name('categories.show')
    ->scopeBindings();

Route::get('/subcategories/{subcategory}', [SubcategoryController::class, 'show'])
    ->name('subcategories.show')
    ->scopeBindings();

Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show')
    ->scopeBindings();

// Listado público de productos
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

Route::get('checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index')
    ->middleware('auth');

Route::post('checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store')
    ->middleware('auth');

Route::get('checkout/thank-you', [CheckoutController::class, 'thankYou'])
    ->name('checkout.thank-you')
    ->middleware('auth');

// Rutas para procesar pagos - requieren autenticación
Route::middleware('auth')->group(function () {
    // Nuevas rutas centralizadas en CheckoutController
    Route::post('/checkout/transfer-payment', [CheckoutController::class, 'storeTransferPayment'])
        ->name('checkout.transfer-payment');

    Route::post('/checkout/qr-payment', [CheckoutController::class, 'storeQrPayment'])
        ->name('checkout.qr-payment');

    // Rutas antiguas del PaymentController (mantener por compatibilidad temporal)
    Route::post('/payments/transfer-receipt', [PaymentController::class, 'uploadTransferReceipt'])
        ->name('payments.transfer-receipt');

    Route::post('/payments/cash-confirm', [PaymentController::class, 'confirmCashPayment'])
        ->name('payments.cash-confirm');

    Route::post('/payments/qr-receipt', [PaymentController::class, 'uploadQrReceipt'])
        ->name('payments.qr-receipt');
});

// Rutas para el centro de notificaciones del cliente
Route::middleware('auth')->group(function () {
    Route::get('/notificaciones', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');

    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
        ->name('notifications.destroy');

    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])
        ->name('notifications.unread');
});

// Rutas para el tracking de órdenes del cliente
Route::middleware('auth')->group(function () {
    Route::get('/mis-pedidos', [OrderTrackingController::class, 'index'])
        ->name('orders.tracking.index');

    Route::get('/pedidos/{order}/tracking', [OrderTrackingController::class, 'show'])
        ->name('orders.tracking.show');

    Route::get('/pedidos/{order}/status', [OrderTrackingController::class, 'status'])
        ->name('orders.tracking.status');

    Route::get('/pedidos/{order}/factura', [CheckoutController::class, 'downloadInvoice'])
        ->name('orders.invoice');
});

// [FUTURO] Rutas para integración PayPhone y PayPal
// Route::post('/payments/payphone-gateway', [PaymentController::class, 'payphoneGateway'])->name('payments.payphone-gateway');
// Route::post('/payments/paypal-gateway', [PaymentController::class, 'paypalGateway'])->name('payments.paypal-gateway');

