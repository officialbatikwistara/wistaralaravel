<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kirim jumlah pesanan ke semua view dalam folder admin
        View::composer('admin.*', function ($view) {
            // Hitung jumlah pesanan dengan status 'pending'
            $jumlahPesanan = Order::where('status', 'pending')->count();

            // Kirim ke view
            $view->with('jumlahPesanan', $jumlahPesanan);
        });
    }
}
