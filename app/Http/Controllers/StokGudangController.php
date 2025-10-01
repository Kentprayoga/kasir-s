<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokGudang;
use App\Models\StokGudangLog;

class StokGudangController extends Controller
{

public function getData()
{
    $stokGudangs = StokGudang::with('logs')->orderBy('nama_barang')->get();

    return response()->json([
        'data' => $stokGudangs
    ]);
}

    // Tampilkan semua stok gudang
    public function index()
    {
        $stokGudangs = StokGudang::with('logs')->orderBy('nama_barang')->get();
        return view('stok_gudang.index', compact('stokGudangs'));
    }

    // Simpan stok gudang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok_tersedia' => 'required|integer|min:0',
        ]);

        $stok = StokGudang::create([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'stok_tersedia' => $request->stok_tersedia,
        ]);

        // Catat log masuk awal
        if($request->stok_tersedia > 0){
            StokGudangLog::create([
                'stok_gudang_id' => $stok->id,
                'stok_masuk' => $request->stok_tersedia,
                'tanggal' => now()->format('Y-m-d'),
                'keterangan' => 'Stok awal',
            ]);
        }

        return redirect()->back()->with('success','Stok gudang berhasil ditambahkan.');
    }

    // Update stok gudang
    public function update(Request $request, $id)
    {
        $stok = StokGudang::findOrFail($id);

        $request->validate([
            'harga' => 'required|numeric|min:0',
            'stok_tersedia' => 'required|integer|min:0',
        ]);

        $stokAwal = $stok->stok_tersedia;

        $stok->update([
            'harga' => $request->harga,
            'stok_tersedia' => $request->stok_tersedia,
        ]);

        // Catat log perubahan stok
        $diff = $request->stok_tersedia - $stokAwal;
        if($diff != 0){
            StokGudangLog::create([
                'stok_gudang_id' => $stok->id,
                'stok_masuk' => $diff > 0 ? $diff : 0,
                'stok_keluar' => $diff < 0 ? abs($diff) : 0,
                'tanggal' => now()->format('Y-m-d'),
                'keterangan' => 'Update stok',
            ]);
        }

        return redirect()->back()->with('success','Stok gudang berhasil diperbarui.');
    }

    // Hapus stok gudang
    public function destroy($id)
    {
        $stok = StokGudang::findOrFail($id);
        $stok->delete();
        return redirect()->back()->with('success','Stok gudang berhasil dihapus.');
    }
}