<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DischubPaymentController;

Route::get('/', function () {
    return view('welcome');
});

// Create Order Route
Route::post('/dischub-order', [DischubPaymentController::class, 'createOrder'])->name('dischub.order');

// List Orders
Route::get('/dischub-orders', [DischubPaymentController::class, 'listOrders'])->name('dischub.orders');

Route::post('/dischub/callback', [DischubPaymentController::class, 'receiveDischubCallback']);

