<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CoverController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryDriverController;
use App\Http\Controllers\Admin\OfficeAddressController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Admin\VerifiedOrderController;
use App\Models\Family;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

Route::get('/options', [OptionController::class, 'index'])->name('options.index');

// Rutas de gestión de usuarios
Route::resource('users', UserController::class);

Route::resource('families', FamilyController::class);
Route::resource('categories', CategoryController::class);
Route::resource('subcategories', SubcategoryController::class);
Route::resource('products', ProductController::class);
Route::get('products/{product}/variants/{variant}', [ProductController::class, 'variants'])
    ->name('products.variants')
    ->scopeBindings();

Route::put('products/{product}/variants/{variant}', [ProductController::class, 'variantsUpdate'])
    ->name('products.variantsUpdate')
    ->scopeBindings();

// Ruta para subida temporal de imágenes de variantes
Route::post('products/variants/upload-temp-image', [ProductController::class, 'uploadTempVariantImage'])
    ->name('products.variants.upload-temp-image');

// Ruta para limpiar flag de toast
Route::post('products/variants/clear-toast-flag', [ProductController::class, 'clearToastFlag'])
    ->name('products.variants.clear-toast-flag');

Route::resource('covers', CoverController::class);

// Rutas de órdenes
Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::get('orders/{order}/download-pdf', [OrderController::class, 'downloadPDF'])->name('orders.downloadPDF');
Route::get('orders/{order}/check-shipment', [OrderController::class, 'checkShipment'])->name('orders.checkShipment');
Route::post('orders/{order}/assign-driver', [OrderController::class, 'assignDriver'])->name('orders.assignDriver');
Route::patch('orders/{order}/mark-in-transit', [OrderController::class, 'markAsInTransit'])->name('orders.markInTransit');
Route::patch('orders/{order}/mark-delivered', [OrderController::class, 'markAsDelivered'])->name('orders.markDelivered');
Route::patch('orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');

// Rutas de repartidores
Route::get('delivery-drivers/active', [DeliveryDriverController::class, 'getActiveDrivers'])->name('delivery-drivers.active');
Route::patch('delivery-drivers/{deliveryDriver}/toggle-status', [DeliveryDriverController::class, 'toggleStatus'])->name('delivery-drivers.toggle-status');
Route::resource('delivery-drivers', DeliveryDriverController::class);

// Rutas de envíos
Route::resource('shipments', ShipmentController::class);
Route::patch('shipments/{shipment}/assign-driver', [ShipmentController::class, 'assignDriver'])->name('shipments.assignDriver');
Route::patch('shipments/{shipment}/mark-picked-up', [ShipmentController::class, 'markPickedUp'])->name('shipments.markPickedUp');
Route::patch('shipments/{shipment}/mark-in-transit', [ShipmentController::class, 'markInTransit'])->name('shipments.markInTransit');
Route::patch('shipments/{shipment}/mark-delivered', [ShipmentController::class, 'markDelivered'])->name('shipments.markDelivered');
Route::patch('shipments/{shipment}/mark-failed', [ShipmentController::class, 'markFailed'])->name('shipments.markFailed');
Route::patch('shipments/{shipment}/update-location', [ShipmentController::class, 'updateLocation'])->name('shipments.updateLocation');

// Rutas de verificación de pagos
Route::get('payments/verification', [PaymentVerificationController::class, 'index'])->name('payments.verification');
Route::patch('payments/{payment}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');
Route::get('payments/stats', [PaymentVerificationController::class, 'getStats'])->name('payments.stats');

// Rutas de configuraciones del sistema
Route::get('settings', [SystemSettingsController::class, 'index'])->name('settings.index');
Route::put('settings', [SystemSettingsController::class, 'update'])->name('settings.update');
Route::get('api/settings/public', [SystemSettingsController::class, 'getPublicSettings'])->name('settings.public');

// Rutas de direcciones de oficinas
Route::patch('office-addresses/{officeAddress}/toggle-status', [OfficeAddressController::class, 'toggleStatus'])->name('office-addresses.toggleStatus');
Route::resource('office-addresses', OfficeAddressController::class);
