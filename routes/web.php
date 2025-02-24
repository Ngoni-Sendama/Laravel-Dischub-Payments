<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DischubPaymentController;

Route::get('/', function () {
    return view('welcome');
});

//  Callback Url
Route::post('/dischub/callback', [DischubPaymentController::class, 'handleCallback']);
