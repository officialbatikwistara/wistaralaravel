<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Str;

class BeritaAdminController extends Controller
{
    public function index()
    {
        $berita = Berita::orderBy('tanggal', 'desc')->get();
        return view('admin.berita.index', compact('berita'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'konten' => 'required',
        'gambar_url' => 'nullable|url',
        'gambar_file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'sumber' => 'nullable|string|max:255',
        'tautan_sumber' => 'nullable|url',
        'tanggal' => 'required|date'
    ]);

    $slug = Str::slug($request->judul);

    $gambar = null;
    if ($request->hasFile('gambar_file')) {
        $gambar = time().'_'.$request->gambar_file->getClientOriginalName();
        $request->gambar_file->move(public_path('uploads/berita'), $gambar);
        $gambar = 'uploads/berita/'.$gambar;
    } elseif ($request->filled('gambar_url')) {
        $gambar = $request->gambar_url;
    }

    Berita::create([
        'judul' => $request->judul,
        'slug' => $slug,
        'konten' => $request->konten,
        'gambar' => $gambar,
        'tanggal' => $request->tanggal, // 🆕 ambil dari input
        'sumber' => $request->sumber,
        'tautan_sumber' => $request->tautan_sumber,
    ]);

    return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan!');
}


    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            'gambar_url' => 'nullable|url',
            'gambar_file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sumber' => 'nullable|string|max:255',
            'tautan_sumber' => 'nullable|url',
        ]);

        $gambar = $berita->gambar;

        if ($request->hasFile('gambar_file')) {
            $gambar = time().'_'.$request->gambar_file->getClientOriginalName();
            $request->gambar_file->move(public_path('uploads/berita'), $gambar);
            $gambar = 'uploads/berita/'.$gambar;
        } elseif ($request->filled('gambar_url')) {
            $gambar = $request->gambar_url;
        }

$berita->update([
    'judul' => $request->judul,
    'slug' => Str::slug($request->judul),
    'konten' => $request->konten,
    'gambar' => $gambar,
    'tanggal' => $request->tanggal, // 🆕 update tanggal
    'sumber' => $request->sumber,
    'tautan_sumber' => $request->tautan_sumber,
]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        // Hapus file jika local upload (bukan URL)
        if ($berita->gambar && !filter_var($berita->gambar, FILTER_VALIDATE_URL)) {
            if (file_exists(public_path($berita->gambar))) {
                unlink(public_path($berita->gambar));
            }
        }

        $berita->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}
