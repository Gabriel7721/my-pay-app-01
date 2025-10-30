<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentReturnController;
use App\Http\Controllers\PaymentWebhookController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get("/products", function () {
    return Inertia::render('Products', ['products' => Product::query()->select(
        'id',
        'slug',
        'name',
        'description',
        'price',
        'currency',
        'image_url',
        'stock'
    )->get()]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/checkout/start', [CheckoutController::class, 'start'])->name('checkout.start');
});

Route::get('/payments/{provider}/return', [PaymentReturnController::class, 'handle']);
Route::post('/webhooks/stripe', [PaymentWebhookController::class, 'stripe'])
    ->name('webhooks.stripe');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__ . '/settings.php';
