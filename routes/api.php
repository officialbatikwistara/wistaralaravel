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

/*
|--------------------------------------------------------------------------
| API Routes for Chatbot Wistara & Review System
|--------------------------------------------------------------------------
| Menyediakan data produk pesanan berita dalam format JSON
| agar chatbot Node.js dapat mengaksesnya secara real-time.
| Dan menyediakan API untuk sistem review produk.
*/

// ✅ Produk
Route::get('/produk', function () {
    return response()->json(
        Produk::select('id_produk', 'nama_produk', 'slug', 'harga', 'stok', 'gambar')
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
    );
});

// ✅ Berita
Route::get('/berita', function () {
    return response()->json(
        Berita::select('id', 'judul', 'slug', 'tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get()
    );
});

// ✅ pesanan
Route::get('/order/{id}', function ($id) {
    $order = Order::where('id', $id)->first();

    if (!$order) {
        return response()->json(['error' => 'Pesanan tidak ditemukan'], 404);
    }

    return response()->json([
        'id' => $order->id,
        'nama' => $order->nama,
        'total' => $order->total,
        'status' => $order->status,
        'status_pembayaran' => $order->status_pembayaran,
        'tanggal' => $order->created_at->format('d/m/Y H:i')
    ]);
});


// 🔹 Login endpoint
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
});

// 🔹 Route API Review - DIPINDAH KE routes/web.php untuk support session auth

/*
|--------------------------------------------------------------------------
| API Health Check & System Status
|--------------------------------------------------------------------------
*/
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
        'environment' => app()->environment(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected'
    ]);
});

/*
|--------------------------------------------------------------------------
| Real-time Notification API v1
|--------------------------------------------------------------------------
| API untuk notifikasi real-time menggunakan Server-Sent Events
| atau polling untuk aplikasi eksternal
|
| Authentication: Bearer Token (Sanctum)
| Rate Limiting: 60 requests per minute per user
| CORS: Enabled for cross-origin requests
| Security: Admin-only access with email validation
*/

// 🔔 Notification API untuk admin
Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('v1/notifications')->group(function () {
    // Get notifications
    Route::get('/', function () {
        try {
            $user = request()->user();
            if (!$user) {
                return response()->json(['error' => 'Authentication required'], 401);
            }

            // Check if user is admin (simple email check for now)
            $isAdmin = str_contains(strtolower($user->email), 'admin') ||
                      $user->email === 'admin@wistara.com' ||
                      $user->email === env('ADMIN_EMAIL');

            if (!$isAdmin) {
                return response()->json(['error' => 'Admin access required'], 403);
            }

            $limit = min(request('limit', 50), 100); // Max 100, default 50
            $notifications = $user->notifications()
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->data['type'] ?? 'general',
                        'message' => $notification->data['message'] ?? '',
                        'order_id' => $notification->data['order_id'] ?? null,
                        'user_name' => $notification->data['user_name'] ?? null,
                        'total' => $notification->data['total'] ?? null,
                        'url' => $notification->data['url'] ?? null,
                        'read' => !is_null($notification->read_at),
                        'created_at' => $notification->created_at->toISOString(),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $notifications,
                'count' => $notifications->count(),
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('API Error - Get Notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    });

    // Get unread count
    Route::get('/count', function () {
        $user = request()->user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $count = $user->unreadNotifications()->count();
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    });

    // Real-time stream (SSE)
    Route::get('/stream', function () {
        $user = request()->user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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
                        'notifications' => $newNotifications->map(function($n) {
                            return [
                                'id' => $n->id,
                                'type' => $n->data['type'] ?? 'general',
                                'message' => $n->data['message'] ?? '',
                                'created_at' => $n->created_at->toISOString(),
                                'read' => !is_null($n->read_at)
                            ];
                        }),
                        'timestamp' => time()
                    ];

                    echo "data: " . json_encode($data) . "\n\n";
                    ob_flush();
                    flush();
                }

                sleep(3); // Check every 3 seconds for API

                if (time() - $lastCheck > 300) { // 5 minutes timeout
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

    // Mark as read
    Route::post('/{id}/read', function ($id) {
        try {
            $user = request()->user();
            if (!$user) {
                return response()->json(['error' => 'Authentication required'], 401);
            }

            // Validate ID
            if (!is_numeric($id) || $id <= 0) {
                return response()->json(['error' => 'Invalid notification ID'], 400);
            }

            // Check if user is admin
            $isAdmin = str_contains(strtolower($user->email), 'admin') ||
                      $user->email === 'admin@wistara.com' ||
                      $user->email === env('ADMIN_EMAIL');

            if (!$isAdmin) {
                return response()->json(['error' => 'Admin access required'], 403);
            }

            $notification = $user->notifications()->find($id);
            if (!$notification) {
                return response()->json(['error' => 'Notification not found'], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('API Error - Mark as Read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    });

    // Mark all as read
    Route::post('/mark-all-read', function () {
        $user = request()->user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    });
});

// WhatsApp Webhook Routes (NO AUTH - untuk terima dari Fonnte/Wablas)
Route::post('/whatsapp/webhook', [WhatsAppWebhookController::class, 'handleIncoming']);
Route::post('/whatsapp/status', [WhatsAppWebhookController::class, 'handleStatus']);

// WhatsApp Send API (WITH AUTH - untuk kirim dari admin)
Route::middleware(['auth:sanctum'])->prefix('whatsapp')->group(function () {
    // Send single message
    Route::post('/send', function (Request $request) {
        try {
            $validated = $request->validate([
                'phone' => 'required|string',
                'message' => 'required|string',
                'image_url' => 'nullable|url'
            ]);

            $whatsapp = app(WhatsAppService::class);

            $result = $whatsapp->sendMessage(
                $validated['phone'],
                $validated['message'],
                $validated['image_url'] ?? null
            );

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('WhatsApp send error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Send bulk messages
    Route::post('/send-bulk', function (Request $request) {
        try {
            $validated = $request->validate([
                'phones' => 'required|array',
                'phones.*' => 'required|string',
                'message' => 'required|string'
            ]);

            $whatsapp = app(WhatsAppService::class);

            $result = $whatsapp->sendBulk(
                $validated['phones'],
                $validated['message']
            );

            return response()->json(['results' => $result]);
        } catch (\Exception $e) {
            Log::error('WhatsApp bulk send error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });
});

// WhatsApp Webhook (No CSRF in api routes)
Route::post('/webhook/whatsapp', [App\Http\Controllers\WhatsAppWebhookController::class, 'webhook']);


