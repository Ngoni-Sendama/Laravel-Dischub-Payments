<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DischubPaymentController;

Route::get('/', function () {
    return view('welcome');
});

// Create Order Route
Route::post('/dischub-order', [DischubPaymentController::class, 'createOrder'])->name('dischub.order');

// Handle Dischub Callback
Route::post('dischub/callback', [DischubPaymentController::class, 'handleCallback'])->name('dischub.callback');

// List Orders
Route::get('/dischub-orders', [DischubPaymentController::class, 'listOrders'])->name('dischub.orders');

// Trigger Callback Manually (for testing purposes)
Route::get('/dischub/trigger-callback/{order_id}', [DischubPaymentController::class, 'triggerCallback'])->name('dischub.triggerCallback');

// Refresh Payment Status (Informational)
Route::post('payment-status/{order_id}', [DischubPaymentController::class, 'checkStatus'])->name('payment.status');
