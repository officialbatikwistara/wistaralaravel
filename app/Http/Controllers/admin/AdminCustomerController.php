<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter Keyword
        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('email', 'like', '%' . $request->keyword . '%')
                    ->orWhere('phone', 'like', '%' . $request->keyword . '%');
            });
        }

        // Filter Tanggal
        if ($request->start) {
            $query->whereDate('created_at', '>=', $request->start);
        }

        if ($request->end) {
            $query->whereDate('created_at', '<=', $request->end);
        }

        // Ambil data + hitung total pesanan (relasi orders)
        $customers = $query->withCount('orders')->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::with('orders')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);

        // Kalau mau hapus pesanan pelanggan juga → uncomment
        // $customer->orders()->delete();

        $customer->delete();

        return back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}
