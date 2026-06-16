<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// The default welcome page (if you have one)
Route::get('/', function () {
    return inertia('Welcome'); 
});

// Your Stripe Checkout Page Route
Route::get('/checkout', [PaymentController::class, 'showCheckout'])->name('checkout.show');
Route::get('/payment-success', function () {
    return inertia('PaymentSuccess');
})->name('payment.success');

Route::get('/payment-history', [PaymentController::class, 'history'])->name('payment.history');

// This maps the URL path "/tutor/payouts" to your controller method
Route::get('/tutor/payouts', [PaymentController::class, 'tutorPayouts'])->name('tutor.payouts');

// Endpoint for processing individual class payout distributions
Route::post('/tutor/disburse-class', [PaymentController::class, 'processClassDisbursement'])->name('tutor.disburse-class');
