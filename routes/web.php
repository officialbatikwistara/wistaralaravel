<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CartController;

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
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

/*
|--------------------------------------------------------------------------
| Route Login & Auth User
|--------------------------------------------------------------------------
*/

// 👤 Login User
Route::get('/login', [UserAuthController::class, 'showUserLogin'])->name('user.login');
Route::post('/login', [UserAuthController::class, 'userLogin'])->name('user.login.post');
Route::get('/logout-user', [UserAuthController::class, 'userLogout'])->name('user.logout');
Route::get('/register', [UserAuthController::class, 'showRegister'])->name('user.register');
Route::post('/register', [UserAuthController::class, 'register'])->name('user.register.post');

// 🛡️ Dashboard User (middleware proteksi)
Route::middleware(['user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});


/*
|--------------------------------------------------------------------------
| Route Login & Auth Admin
|--------------------------------------------------------------------------
*/

// 🔐 Login Admin
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::get('/logout-admin', [AuthController::class, 'adminLogout'])->name('admin.logout');

// 🛡️ Dashboard Admin (butuh middleware admin)
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});
