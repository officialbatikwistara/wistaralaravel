<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadBuktiController extends Controller
{
    public function index()
    {
        return view('upload-bukti.index');
    }

    public function store(Request $request)
    {
        // Implementasi upload bukti
        return response()->json(['success' => true]);
    }
}
