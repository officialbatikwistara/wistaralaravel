<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Total pesanan selesai oleh user
        $totalSelesai = Order::where('user_id', $userId)
            ->where('status', 'selesai')
            ->count();

        // Tentukan level user
        $level = $this->getUserLevel($totalSelesai);

        return view('user.dashboard', compact('totalSelesai', 'level'));
    }

    private function getUserLevel($count)
    {
        if ($count >= 50) {
            return [
                'label' => 'Diamond',
                'color' => '#0dcaf0',
                'icon'  => 'fa-gem'
            ];
        } elseif ($count >= 20) {
            return [
                'label' => 'Platinum',
                'color' => '#6c63ff',
                'icon'  => 'fa-crown'
            ];
        } elseif ($count >= 10) {
            return [
                'label' => 'Gold',
                'color' => '#ffcc00',
                'icon'  => 'fa-star'
            ];
        } elseif ($count >= 5) {
            return [
                'label' => 'Silver',
                'color' => '#adb5bd',
                'icon'  => 'fa-medal'
            ];
        } else {
            return [
                'label' => 'Bronze',
                'color' => '#cd7f32',
                'icon'  => 'fa-award'
            ];
        }
    }
}
