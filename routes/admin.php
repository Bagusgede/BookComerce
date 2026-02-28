<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\EbookDeliveryController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\CKEditorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/stats', [DashboardController::class, 'stats'])->name('stats');
    Route::get('/api/chart-data', [DashboardController::class, 'chartData'])->name('chart-data');

    // Books Management
    Route::resource('books', BookController::class);
    Route::post('/books/bulk-update', [BookController::class, 'bulkUpdate'])->name('books.bulk-update');
    Route::get('/books-export', [BookController::class, 'export'])->name('books.export');

    // Authors Management
    Route::resource('authors', AuthorController::class);

    // Categories Management
    Route::resource('categories', CategoryController::class);

    // Orders Management
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/shipping', [OrderController::class, 'submitShipping'])->name('orders.submit-shipping');
    Route::post('/orders/{order}/{orderItem}/resend-ebook', [OrderController::class, 'resendEbook'])->name('orders.resend-ebook');
    Route::get('/orders-export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders-analytics', [OrderController::class, 'analytics'])->name('orders.analytics');

    // Ebook Deliveries Management
    Route::resource('ebook-deliveries', EbookDeliveryController::class)->only(['index', 'show', 'destroy']);
    Route::post('/ebook-deliveries/{delivery}/resend', [EbookDeliveryController::class, 'resend'])->name('ebook-deliveries.resend');
    Route::post('/ebook-deliveries/{delivery}/regenerate-token', [EbookDeliveryController::class, 'regenerateToken'])->name('ebook-deliveries.regenerate-token');
    Route::get('/ebook-deliveries-logs', [EbookDeliveryController::class, 'logs'])->name('ebook-deliveries.logs');
    Route::get('/ebook-deliveries-export', [EbookDeliveryController::class, 'export'])->name('ebook-deliveries.export');
    Route::get('/ebook-deliveries-analytics', [EbookDeliveryController::class, 'analytics'])->name('ebook-deliveries.analytics');

    // Payments Management
    Route::resource('payments', PaymentController::class)->only(['index', 'show']);
    Route::post('/payments/{order}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{order}/mark-failed', [PaymentController::class, 'markFailed'])->name('payments.mark-failed');
    Route::post('/payments/{order}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
    Route::get('/payments-logs', [PaymentController::class, 'logs'])->name('payments.logs');
    Route::get('/payments-analytics', [PaymentController::class, 'analytics'])->name('payments.analytics');
    Route::get('/payments-export', [PaymentController::class, 'export'])->name('payments.export');

    // Blog Management
    // CKEditor image upload (used by admin blog editor)
    Route::post('/blog/ckeditor-upload', [CKEditorController::class, 'upload'])->name('blog.ckeditor.upload');
    Route::get('/blog', [BlogPostController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [BlogPostController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogPostController::class, 'store'])->name('blog.store');
    Route::get('/blog/{post}', [BlogPostController::class, 'show'])->name('blog.show');
    Route::get('/blog/{post}/edit', [BlogPostController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{post}', [BlogPostController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [BlogPostController::class, 'destroy'])->name('blog.destroy');
    Route::post('/blog/{post}/publish', [BlogPostController::class, 'publish'])->name('blog.publish');
    Route::post('/blog/{post}/unpublish', [BlogPostController::class, 'unpublish'])->name('blog.unpublish');

    // Customers Management
    Route::resource('customers', CustomerController::class)->only(['index', 'show']);
    Route::post('/customers/{email}/send-message', [CustomerController::class, 'sendMessage'])->name('customers.send-message');
    Route::get('/customers-export', [CustomerController::class, 'export'])->name('customers.export');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/logs', [SettingsController::class, 'logs'])->name('settings.logs');
    Route::post('/cache-clear', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/migrate', [SettingsController::class, 'migrate'])->name('settings.migrate');
    Route::post('/backup', [SettingsController::class, 'backup'])->name('settings.backup');
});
