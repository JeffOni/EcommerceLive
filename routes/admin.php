<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CoverController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryDriverController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\VerifiedOrderController;
use App\Models\Family;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubcategoryController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

Route::get('/options', [OptionController::class, 'index'])->name('options.index');

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

// Rutas de repartidores
Route::get('delivery-drivers/active', [DeliveryDriverController::class, 'getActiveDrivers'])->name('delivery-drivers.active');
Route::patch('delivery-drivers/{deliveryDriver}/toggle-status', [DeliveryDriverController::class, 'toggleStatus'])->name('delivery-drivers.toggleStatus');
Route::resource('delivery-drivers', DeliveryDriverController::class);

// Rutas de envíos
Route::resource('shipments', ShipmentController::class);
Route::patch('shipments/{shipment}/assign-driver', [ShipmentController::class, 'assignDriver'])->name('shipments.assignDriver');
Route::patch('shipments/{shipment}/mark-picked-up', [ShipmentController::class, 'markPickedUp'])->name('shipments.markPickedUp');
Route::patch('shipments/{shipment}/mark-delivered', [ShipmentController::class, 'markDelivered'])->name('shipments.markDelivered');
Route::patch('shipments/{shipment}/mark-failed', [ShipmentController::class, 'markFailed'])->name('shipments.markFailed');
Route::patch('shipments/{shipment}/update-location', [ShipmentController::class, 'updateLocation'])->name('shipments.updateLocation');

// Rutas de verificación de pagos
Route::get('payments/verification', [PaymentVerificationController::class, 'index'])->name('payments.verification');
Route::patch('payments/{payment}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');
Route::get('payments/stats', [PaymentVerificationController::class, 'getStats'])->name('payments.stats');
