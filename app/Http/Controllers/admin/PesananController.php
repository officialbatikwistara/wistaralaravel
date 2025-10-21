<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $pesanan = Order::query();

    if ($request->search) {
        $pesanan->where('nama', 'like', "%{$request->search}%")
            ->orWhere('id', 'like', "%{$request->search}%");
    }

    if ($request->start_date && $request->end_date) {
        $pesanan->whereBetween('created_at', [
            $request->start_date,
            $request->end_date . ' 23:59:59',
        ]);
    }

    $orders = $pesanan->orderBy('created_at', 'desc')->paginate(10);

    // kalau ada parameter detail_id, ambil datanya
    $detail = null;
    if ($request->has('detail_id')) {
        $detail = Order::with('items.product')->find($request->detail_id);
    }

    return view('admin.pesanan.index', compact('orders', 'detail'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $order = Order::with('items.product')->findOrFail($id);

    return view('admin.pesanan.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
