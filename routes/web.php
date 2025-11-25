<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Berita;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UserReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProdukAdminController;
use App\Http\Controllers\Admin\KategoriAdminController;
use App\Http\Controllers\Admin\BeritaAdminController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\UploadBuktiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', function () { return view('tentang'); })->name('tentang');
Route::get('/kontak', function () { return view('kontak'); })->name('kontak');

// Produk
Route::get('/katalog', [ProdukController::class, 'index'])->name('katalog');
Route::get('/produk/{slug}', [ProdukController::class, 'show'])->name('produk.show');

// Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail');

// Review
Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');

// User Authentication
Route::get('/login', [UserAuthController::class, 'showUserLogin'])->name('login');
Route::post('/login', [UserAuthController::class, 'userLogin'])->name('user.login.post');
Route::get('/register', [UserAuthController::class, 'showRegister'])->name('user.register');
Route::post('/register', [UserAuthController::class, 'register'])->name('user.register.post');
Route::get('/logout-user', [UserAuthController::class, 'userLogout'])->name('user.logout');
Route::get('/check-user', [UserAuthController::class, 'checkUser'])->name('check.user');

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{produkId}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::get('/checkout/{id_produk?}', [CheckoutController::class, 'index'])->name('checkout');
Route::get('/checkout', [CheckoutController::class, 'cartCheckout'])->name('checkout.index');
Route::get('/checkout/{id_produk}', [CheckoutController::class, 'directCheckout'])->name('checkout.direct');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/qris/{id}', [CheckoutController::class, 'qris'])->name('checkout.qris');
Route::get('/checkout/bank-transfer/{id}', [CheckoutController::class, 'bankTransfer'])->name('checkout.bank');
// Route::post('/checkout/{id}/upload-bukti', [UploadBuktiController::class, 'upload'])->name('checkout.uploadBukti');

// User Dashboard (Protected)
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::put('/update-profile', [UserAuthController::class, 'updateProfile'])->name('user.update.profile');
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Orders
    Route::get('/orders', [UserOrderController::class, 'index'])->name('user.orders');
    Route::get('/orders/{id}', [UserOrderController::class, 'show'])->name('user.order.show');
    Route::post('/orders/{id}/cancel', [UserOrderController::class, 'cancel'])->name('user.order.cancel');
    Route::post('/orders/{id}/upload-bukti', [UserOrderController::class, 'uploadBukti'])->name('user.order.uploadBukti');
    Route::get('/orders/{order}/invoice', [UserOrderController::class, 'invoice'])->name('user.orders.invoice');
    Route::get('/orders/{order}/invoice/pdf', [UserOrderController::class, 'invoicePDF'])->name('user.order.invoice.pdf');

    // Reviews
    Route::get('/reviews', [UserReviewController::class, 'index'])->name('user.reviews.index');
    Route::get('/reviews/{id}/edit', [UserReviewController::class, 'edit'])->name('user.reviews.edit');
    Route::patch('/reviews/{id}', [UserReviewController::class, 'update'])->name('user.reviews.update');
    Route::delete('/reviews/{id}', [UserReviewController::class, 'destroy'])->name('user.reviews.destroy');
});

// Notifications
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', function () {
        return view('notifications.index');
    });
    Route::get('/notifications/check', function () {
        return response()->json([
            'count' => auth()->user()->unreadNotifications->count()
        ]);
    });
    Route::post('/notifications/{id}/mark-as-read', function ($id) {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    });
    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    });
    Route::get('/notifications/stream', function () {
        return response()->stream(function () {
            while (true) {
                $count = auth()->user()->unreadNotifications->count();
                echo "data: " . json_encode(['count' => $count]) . "\n\n";
                ob_flush();
                flush();
                sleep(5);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    });
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Login (public)
    Route::get('/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

    // Default admin redirect
    Route::get('/', function () {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    })->name('admin.home');

    // Protected admin routes - use 'auth:admin' middleware
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Produk
        Route::get('/produk', [ProdukAdminController::class, 'index'])->name('admin.produk.index');
        Route::get('/produk/create', [ProdukAdminController::class, 'create'])->name('admin.produk.create');
        Route::post('/produk', [ProdukAdminController::class, 'store'])->name('admin.produk.store');
        Route::get('/produk/{id}/edit', [ProdukAdminController::class, 'edit'])->name('admin.produk.edit');
        Route::put('/produk/{id}', [ProdukAdminController::class, 'update'])->name('admin.produk.update');
        Route::delete('/produk/{id}', [ProdukAdminController::class, 'destroy'])->name('admin.produk.delete');
        Route::patch('/produk/{id}/nonaktif', [ProdukController::class, 'nonaktif'])->name('admin.produk.nonaktif');
        Route::patch('/produk/{id}/aktifkan', [ProdukController::class, 'aktifkan'])->name('admin.produk.aktifkan');

        // Kategori
        Route::get('/kategori', [KategoriAdminController::class, 'index'])->name('admin.kategori.index');
        Route::resource('kategori', KategoriAdminController::class)
            ->except(['index'])
            ->names([
                'create' => 'admin.kategori.create',
                'store' => 'admin.kategori.store',
                'show' => 'admin.kategori.show',
                'edit' => 'admin.kategori.edit',
                'update' => 'admin.kategori.update',
                'destroy' => 'admin.kategori.delete',
            ]);

        // Berita
        Route::get('/berita', [BeritaAdminController::class, 'index'])->name('admin.berita.index');
        Route::resource('berita', BeritaAdminController::class)
            ->except(['index'])
            ->names([
                'create' => 'admin.berita.create',
                'store' => 'admin.berita.store',
                'show' => 'admin.berita.show',
                'edit' => 'admin.berita.edit',
                'update' => 'admin.berita.update',
                'destroy' => 'admin.berita.delete',
            ]);

        // Pesanan
        Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/pesanan/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::patch('/pesanan/{id}/update-payment', [AdminOrderController::class, 'updatePayment'])->name('admin.orders.updatePayment');
        Route::patch('/pesanan/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

        // Reviews
        Route::get('/reviews', [ReviewAdminController::class, 'index'])->name('admin.reviews.index');
        Route::get('/reviews/{id}/edit', [ReviewAdminController::class, 'edit'])->name('admin.reviews.edit');
        Route::patch('/reviews/{id}', [ReviewAdminController::class, 'update'])->name('admin.reviews.update');
        Route::patch('/reviews/{id}/approve', [ReviewAdminController::class, 'approve'])->name('admin.reviews.approve');
        Route::patch('/reviews/{id}/reject', [ReviewAdminController::class, 'reject'])->name('admin.reviews.reject');
        Route::delete('/reviews/{id}', [ReviewAdminController::class, 'destroy'])->name('admin.reviews.delete');
    });
});

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('home')->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Link verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Storage link helper (if storage:link fails)
Route::get('/link-storage', function () {
    if (file_exists(public_path('storage'))) {
        return 'Storage already linked!';
    }

    try {
        $target = storage_path('app/public');
        $link = public_path('storage');

        if (PHP_OS_FAMILY === 'Windows') {
            exec("mklink /D \"$link\" \"$target\"", $output, $return);
            if ($return === 0) {
                return 'Storage linked successfully via mklink!';
            } else {
                return 'Failed to create symbolic link. Error: ' . implode("\n", $output);
            }
        } else {
            symlink($target, $link);
            return 'Storage linked successfully via symlink!';
        }
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->name('link.storage');

// Temporary comment jika controller belum ada
// Route::get('/upload-bukti', [App\Http\Controllers\UploadBuktiController::class, 'index']);
// Route::post('/upload-bukti', [App\Http\Controllers\UploadBuktiController::class, 'store']);

// Chatbot Routes
Route::get('/chatbot', [App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/chat', [App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::post('/chatbot/clear', [App\Http\Controllers\ChatbotController::class, 'clearHistory'])->name('chatbot.clear');
Route::get('/chatbot/test', [App\Http\Controllers\ChatbotController::class, 'test'])->name('chatbot.test');

// WhatsApp Webhook (Groq AI Enabled)
Route::post('/webhook/whatsapp', [App\Http\Controllers\WhatsAppWebhookController::class, 'webhook'])->name('webhook.whatsapp');
Route::delete('/webhook/history/{phone}', [App\Http\Controllers\WhatsAppWebhookController::class, 'clearHistory'])->name('webhook.clear');
