<?php 

use Illuminate\Support\Facades\Route;
use Vendor\Ecommerce\Controllers\ProductController;
use Vendor\Ecommerce\Controllers\PaymentController;
use Vendor\Ecommerce\Controllers\Admin\PaymentGatewayController;
use Vendor\Ecommerce\Controllers\InvoiceController;
use Vendor\Ecommerce\Controllers\Payment\PaymentController AS OnlinePaymentController;

Auth::routes();
Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('ecommerce')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/statistics', [ProductController::class, 'statistics'])->name('products.statistics');
        Route::get('/products/export/{format}', [ProductController::class, 'export'])->name('products.export');
        Route::post('/products/{id}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        // Dans routes/web.php
        Route::post('products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
        // Routes supplémentaires pour la gestion avancée
        Route::delete('products/{product}/force', [ProductController::class, 'forceDestroy'])->name('products.force-destroy');
        Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    });
Route::prefix('payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.update-status');
    Route::post('/{payment}/note', [PaymentController::class, 'addNote'])->name('payments.add-note');
    Route::get('/{payment}/receipt', [PaymentController::class, 'downloadReceipt'])->name('payments.receipt');
    Route::post('/{payment}/send-receipt', [PaymentController::class, 'sendReceipt'])->name('payments.send-receipt');
    Route::get('/payments/statistics', [PaymentController::class, 'statistics'])->name('payments.statistics');
});

// Payment routes
Route::prefix('payments')->name('payments.')->group(function () {
    Route::post('checkout', [OnlinePaymentController::class, 'checkout'])->name('checkout');
    Route::get('success', [OnlinePaymentController::class, 'success'])->name('success');
    Route::get('cancel', [OnlinePaymentController::class, 'cancel'])->name('cancel');
    Route::get('failed', [OnlinePaymentController::class, 'failed'])->name('failed');
    Route::get('gateways', [OnlinePaymentController::class, 'getGateways'])->name('gateways');
});

// Webhooks (pas de CSRF)
Route::post('webhook/paypal', [OnlinePaymentController::class, 'webhook'])->name('payments.webhook.paypal');
Route::post('webhook/stripe', [OnlinePaymentController::class, 'webhook'])->name('payments.webhook.stripe');

// Dans routes/web.php
Route::prefix('admin/payment')->name('admin.payment.')->group(function () {
    Route::get('gateways', [PaymentGatewayController::class, 'index'])->name('gateways');
    Route::get('get-config', [PaymentGatewayController::class, 'getConfig'])->name('get-config');
    Route::post('save-config', [PaymentGatewayController::class, 'saveConfig'])->name('save-config');
    Route::post('test/stripe', [PaymentGatewayController::class, 'testStripe'])->name('test-stripe');
    Route::post('test/paypal', [PaymentGatewayController::class, 'testPayPal'])->name('test-paypal');
    Route::post('test/{gateway}', [PaymentGatewayController::class, 'testConnection'])->name('test');
});


// routes/web.php
Route::resource('invoices', InvoiceController::class);
Route::post('invoices/{invoice}/send', [InvoiceController::class, 'sendEmail'])->name('invoices.send');
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
Route::get('invoices/statistics/data', [InvoiceController::class, 'statistics'])->name('invoices.statistics');
});