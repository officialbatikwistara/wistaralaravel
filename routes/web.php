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
| Route Halaman Utama & Static Page
|--------------------------------------------------------------------------
*/
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
Route::get('/produk/{slug}', [ProdukController::class, 'show'])->name('produk.show');
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
// 🔸 Checkout langsung 1 produk (Beli Sekarang)
Route::get('/checkout/{id_produk}', [CheckoutController::class, 'index'])
    ->middleware('auth')
    ->name('checkout.direct');
/*
|--------------------------------------------------------------------------
| Route Order Customer
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/user/orders', [UserOrderController::class, 'index'])->name('user.orders');
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

Route::get('/checkout/{id_produk?}', [CheckoutController::class, 'index'])
    ->middleware('auth')
    ->name('checkout');

    // 📝 User Reviews
Route::middleware('auth')->group(function () {
    Route::get('/user/reviews', [UserReviewController::class, 'index'])->name('user.reviews.index');
    Route::get('/user/reviews/{id}/edit', [\App\Http\Controllers\UserReviewController::class, 'edit'])->name('user.reviews.edit');
    Route::patch('/user/reviews/{id}', [\App\Http\Controllers\UserReviewController::class, 'update'])->name('user.reviews.update');
    Route::delete('/user/reviews/{id}', [\App\Http\Controllers\UserReviewController::class, 'destroy'])->name('user.reviews.destroy');
});

// 🎫 Coupon validation
Route::middleware('auth')->post('/api/coupons/validate', function (Request $request) {
    $request->validate([
        'code' => 'required|string',
        'total' => 'required|numeric|min:0'
    ]);

    $coupon = \App\Models\Coupon::where('code', $request->code)->first();

    if (!$coupon) {
        return response()->json(['valid' => false, 'message' => 'Kupon tidak ditemukan']);
    }

    if (!$coupon->isValid()) {
        return response()->json(['valid' => false, 'message' => 'Kupon tidak valid atau sudah kadaluarsa']);
    }

    $discount = $coupon->calculateDiscount($request->total);

    if ($discount <= 0) {
        return response()->json(['valid' => false, 'message' => 'Kupon tidak dapat diterapkan untuk total ini']);
    }

    return response()->json([
        'valid' => true,
        'discount' => $discount,
        'coupon_id' => $coupon->id,
        'message' => 'Kupon valid'
    ]);
});

// Invoice routes
Route::get('/user/orders/{order}/invoice', 
    [App\Http\Controllers\UserOrderController::class, 'invoice']
)->name('user.orders.invoice')
  ->middleware('auth');
  
Route::get('/user/orders/{order}/invoice/pdf', 
    [App\Http\Controllers\InvoiceController::class, 'download']
)->name('user.order.invoice.pdf')->middleware('auth');

// REVIEW
Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');
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

// 🔍 Cek email dan nomor HP yang sudah terdaftar
Route::get('/check-user', function (Illuminate\Http\Request $request) {
    $exists = false;
    $type = null;

    if ($request->has('email')) {
        $exists = \App\Models\User::where('email', $request->email)->exists();
        $type = 'email';
    }

    if ($request->has('phone')) {
        $exists = \App\Models\User::where('phone', $request->phone)->exists();
        $type = 'phone';
    }

    return response()->json([
        'exists' => $exists,
        'type' => $type
    ]);
})->name('check.user');

// 🔑 Get CSRF token for API testing
Route::get('/api/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
});

// 🔔 Notification routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->latest()->limit(10)->get();
        return response()->json(['notifications' => $notifications]);
    });

    Route::get('/notifications/check', function () {
        $user = auth()->user();
        $count = $user->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    });

    Route::get('/notifications/stream', function () {
        $user = auth()->user();
        $lastCount = (int) request('last_count', 0);

        return response()->stream(function () use ($user, $lastCount) {
            $lastCheck = time();

            while (true) {
                $currentCount = $user->unreadNotifications()->count();

                if ($currentCount > $lastCount) {
                    $newNotifications = $user->notifications()
                        ->where('created_at', '>', now()->subSeconds(30))
                        ->latest()
                        ->limit(5)
                        ->get();

                    $data = [
                        'count' => $currentCount,
                        'new_count' => $currentCount - $lastCount,
                        'notifications' => $newNotifications,
                        'timestamp' => time()
                    ];

                    echo "data: " . json_encode($data) . "\n\n";
                    ob_flush();
                    flush();
                }

                // Check every 5 seconds
                sleep(5);

                // Timeout after 5 minutes
                if (time() - $lastCheck > 300) {
                    break;
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Cache-Control',
        ]);
    });

    Route::post('/notifications/{id}/mark-as-read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    });

    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    });
});

/*
|--------------------------------------------------------------------------
| Route Login & Auth Admin
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');
Route::get('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/
Route::get('/admin/dashboard', function () {
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
    }
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| Produk Admin
|--------------------------------------------------------------------------
*/
Route::get('/admin/produk', function (Request $request) {
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
    }
    return app(\App\Http\Controllers\Admin\ProdukAdminController::class)->index($request);
})->name('admin.produk.index');

Route::get('/admin/produk/create', function () {
    if (!session()->has('admin_logged_in')) return redirect()->route('admin.login');
    return app(ProdukAdminController::class)->create();
})->name('admin.produk.create');

Route::post('/admin/produk', function () {
    if (!session()->has('admin_logged_in')) return redirect()->route('admin.login');
    return app(ProdukAdminController::class)->store(request());
})->name('admin.produk.store');

Route::get('/admin/produk/{id}/edit', function ($id) {
    if (!session()->has('admin_logged_in')) return redirect()->route('admin.login');
    return app(ProdukAdminController::class)->edit($id);
})->name('admin.produk.edit');

Route::put('/admin/produk/{id}', function ($id) {
    if (!session()->has('admin_logged_in')) return redirect()->route('admin.login');
    return app(ProdukAdminController::class)->update(request(), $id);
})->name('admin.produk.update');

Route::delete('/admin/produk/{id}', function ($id) {
    if (!session()->has('admin_logged_in')) return redirect()->route('admin.login');
    return app(ProdukAdminController::class)->destroy($id);
})->name('admin.produk.delete');

Route::patch('/admin/produk/{id}/nonaktif', function ($id) {
    if (!session()->has('admin_logged_in')) return redirect()->route('admin.login');
    return app(ProdukController::class)->nonaktif($id);
})->name('admin.produk.nonaktif');

Route::patch('/admin/produk/{id}/aktifkan', function ($id) {
    if (!session()->has('admin_logged_in')) return redirect()->route('admin.login');
    return app(ProdukController::class)->aktifkan($id);
})->name('admin.produk.aktifkan');

/*
|--------------------------------------------------------------------------
| Kategori Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    // Index kategori dengan proteksi login
    Route::get('/kategori', function () {
        if (!session()->has('admin_logged_in')) return redirect()->route('admin.login');
        return app(\App\Http\Controllers\Admin\KategoriAdminController::class)->index();
    })->name('admin.kategori.index');

    // Resource route kategori
    Route::resource('kategori', \App\Http\Controllers\Admin\KategoriAdminController::class)
        ->except(['index'])
        ->names([
            'create' => 'admin.kategori.create',
            'store' => 'admin.kategori.store',
            'show' => 'admin.kategori.show',
            'edit' => 'admin.kategori.edit',
            'update' => 'admin.kategori.update',
            'destroy' => 'admin.kategori.delete',
        ]);
});

/*
|--------------------------------------------------------------------------
| Pesanan Admin
|--------------------------------------------------------------------------
*/
Route::get('/admin/pesanan', [AdminOrderController::class, 'index'])
    ->name('admin.orders.index');
Route::get('/admin/pesanan/{id}', [AdminOrderController::class, 'show'])
    ->name('admin.orders.show');
Route::patch('/admin/pesanan/{id}/update-payment', [AdminOrderController::class, 'updatePayment'])
    ->name('admin.orders.updatePayment');
Route::patch('/admin/pesanan/{id}/update-status', [AdminOrderController::class, 'updateStatus'])
    ->name('admin.orders.updateStatus');

/*
|--------------------------------------------------------------------------
| Berita Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    Route::get('/berita', function () {
        if (!session()->has('admin_logged_in')) return redirect()->route('admin.login');
        return app(\App\Http\Controllers\Admin\BeritaAdminController::class)->index();
    })->name('admin.berita.index');

    Route::resource('berita', \App\Http\Controllers\Admin\BeritaAdminController::class)
        ->except(['index'])
        ->names([
            'create' => 'admin.berita.create',
            'store' => 'admin.berita.store',
            'show' => 'admin.berita.show',
            'edit' => 'admin.berita.edit',
            'update' => 'admin.berita.update',
            'destroy' => 'admin.berita.delete',
        ]);
});

/*
|--------------------------------------------------------------------------
| Review API Routes (menggunakan web middleware untuk session auth)
|--------------------------------------------------------------------------
*/
// Public routes
Route::get('/api/reviews', [ReviewController::class, 'index']);
Route::get('/api/reviews/{id}', [ReviewController::class, 'show']);

// Protected routes (butuh login)
Route::middleware('auth')->group(function () {
    Route::post('/api/reviews', [ReviewController::class, 'store']);
    Route::patch('/api/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/api/reviews/{id}', [ReviewController::class, 'destroy']);
    Route::post('/api/reviews/{id}/helpful', [ReviewController::class, 'markHelpful']);
});

/*
|--------------------------------------------------------------------------
| Review Admin (Moderasi)
|--------------------------------------------------------------------------
*/
Route::get('/admin/reviews', function (Request $request) {
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
    }
    return app(\App\Http\Controllers\Admin\ReviewAdminController::class)->index($request);
})->name('admin.reviews.index');

Route::get('/admin/reviews/{id}/edit', function ($id) {
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\Admin\ReviewAdminController::class)->edit($id);
})->name('admin.reviews.edit');

Route::patch('/admin/reviews/{id}', function ($id, Request $request) {
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\Admin\ReviewAdminController::class)->update($request, $id);
})->name('admin.reviews.update');

Route::patch('/admin/reviews/{id}/approve', function ($id) {
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\Admin\ReviewAdminController::class)->approve($id);
})->name('admin.reviews.approve');

Route::patch('/admin/reviews/{id}/reject', function ($id) {
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\Admin\ReviewAdminController::class)->reject($id);
})->name('admin.reviews.reject');

Route::delete('/admin/reviews/{id}', function ($id) {
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\Admin\ReviewAdminController::class)->destroy($id);
})->name('admin.reviews.delete');


// Chatbot Routes
Route::get('/chatbot', [App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/chat', [App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::post('/chatbot/clear', [App\Http\Controllers\ChatbotController::class, 'clearHistory'])->name('chatbot.clear');
Route::get('/chatbot/test', [App\Http\Controllers\ChatbotController::class, 'test'])->name('chatbot.test');

// WhatsApp Webhook - Update ke versi AI
Route::post('/webhook/whatsapp', [App\Http\Controllers\WhatsAppWebhookController::class, 'webhook']);

// Health Check
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);

})->name('health.check');// Products route
Route::get('/produk', function() {
    return view('produk.index');
})->name('produk.index');
