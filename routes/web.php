<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\Admin\ProdukAdminController;
use App\Http\Controllers\Admin\BeritaAdminController;
use App\Http\Controllers\Admin\KategoriAdminController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\AdminOrderController;


use Illuminate\Http\Request;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Route Halaman Utama & Static Page
|--------------------------------------------------------------------------
*/
// HOME
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/kontak', 'kontak')->name('kontak');

/*
|--------------------------------------------------------------------------
| Route Berita
|--------------------------------------------------------------------------
*/

Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail');

/*
|--------------------------------------------------------------------------
| Route Produk / Katalog
|--------------------------------------------------------------------------
*/

Route::get('/katalog', [ProdukController::class, 'index'])->name('katalog');
Route::get('/katalog', [ProdukController::class, 'index'])->name('katalog');
/*
|--------------------------------------------------------------------------
| Route Keranjang
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{produkId}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

/*
|--------------------------------------------------------------------------
| Route Checkout
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

/*
|--------------------------------------------------------------------------
| Route Order Customer
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/user/orders/{id}', [UserOrderController::class, 'show'])->name('user.order.show');
    Route::post('/user/orders/{id}/upload-bukti', [UserOrderController::class, 'uploadBukti'])->name('user.order.uploadBukti');
    Route::post('/user/orders/{id}/cancel', [UserOrderController::class, 'cancel'])->name('user.order.cancel');
});

Route::post('/checkout/{id}/upload-bukti', [UserOrderController::class, 'uploadBukti'])
    ->name('checkout.uploadBukti');

// 🔸 Halaman transfer bank (setelah order dibuat)
Route::get('/checkout/bank-transfer/{id}', function($id) {
    $order = \App\Models\Order::findOrFail($id);
    return view('checkout.bank-transfer', compact('order'));
})->name('checkout.bank');

// 🔸 Halaman QRIS (sementara placeholder)
Route::get('/checkout/qris/{id}', function($id) {
    $order = \App\Models\Order::findOrFail($id);
    return view('checkout.qris', compact('order'));
})->name('checkout.qris');

/*
|--------------------------------------------------------------------------
| Route Login & Auth User
|--------------------------------------------------------------------------
*/

// 👤 Login User
Route::get('/login', [UserAuthController::class, 'showUserLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\UserAuthController::class, 'userLogin'])->name('user.login.post');

// 👤 Logout User
Route::get('/logout-user', [UserAuthController::class, 'userLogout'])->name('user.logout');
Route::get('/register', [UserAuthController::class, 'showRegister'])->name('user.register');
Route::post('/register', [UserAuthController::class, 'register'])->name('user.register.post');

// 👤 edit User
Route::middleware('auth')->group(function () {
    Route::put('/user/update-profile', [\App\Http\Controllers\UserAuthController::class, 'updateProfile'])
        ->name('user.update.profile');
});

// 🛡️ Dashboard User (middleware proteksi)
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    });
});

// Halaman verifikasi email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Proses klik link verifikasi
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Kirim ulang email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ✨ Reset Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Route Login & Auth Admin
|--------------------------------------------------------------------------
*/
// 🔐 Login Admin
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

// 🚪 Logout Admin
Route::get('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

// 📊 Dashboard Admin
Route::get('/admin/dashboard', function () {
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin.');
    }

    return view('admin.dashboard');
})->name('admin.dashboard');

Route::resource('admin/produk', ProdukAdminController::class)->names([
    'index' => 'admin.produk.index',
    'create' => 'admin.produk.create',
    'store' => 'admin.produk.store',
    'show' => 'admin.produk.show',
    'edit' => 'admin.produk.edit',
    'update' => 'admin.produk.update',
    'destroy' => 'admin.produk.delete',
]);

Route::resource('admin/kategori', KategoriAdminController::class)->names([
    'index' => 'admin.kategori.index',
    'create' => 'admin.kategori.create',
    'store' => 'admin.kategori.store',
    'show' => 'admin.kategori.show',
    'edit' => 'admin.kategori.edit',
    'update' => 'admin.kategori.update',
    'destroy' => 'admin.kategori.delete',
]);

// 📦 Pesanan Admin
Route::prefix('admin')->group(function () {
    Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/pesanan/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/pesanan/{id}/update-payment', [AdminOrderController::class, 'updatePayment'])->name('admin.orders.updatePayment');

    // 🆕 Tambahkan route untuk update status
    Route::patch('/pesanan/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});


Route::resource('admin/berita', BeritaAdminController::class)->names([
    'index' => 'admin.berita.index',
    'create' => 'admin.berita.create',
    'store' => 'admin.berita.store',
    'show' => 'admin.berita.show',
    'edit' => 'admin.berita.edit',
    'update' => 'admin.berita.update',
    'destroy' => 'admin.berita.delete',
]);



