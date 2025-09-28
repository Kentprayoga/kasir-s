<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StokTokoLog;

class StokTokoController extends Controller
{
    // Tampilkan semua stok toko (products + log)
    public function index()
    {
        $products = Product::with('stokTokoLogs')->orderBy('nama_barang')->get();
        return view('stok_toko.index', compact('products'));
    }

    // Masukkan stok ke toko (misal dari masak)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stok_masuk' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Update stok toko
        $product->increment('stok', $request->stok_masuk);

        // Catat log
        StokTokoLog::create([
            'product_id' => $product->id,
            'stok_masuk' => $request->stok_masuk,
            'tanggal' => now()->format('Y-m-d'),
            'keterangan' => $request->keterangan ?? 'Masuk stok toko',
        ]);

        return redirect()->back()->with('success','Stok toko berhasil ditambahkan.');
    }

    // Kurangi stok toko (misal retur atau hilang)
    public function update(Request $request, $id)
    {
        $request->validate([
            'stok_keluar' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);

        $stokAwal = $product->stok;
        $stokKeluar = $request->stok_keluar;

        if($stokKeluar > $stokAwal){
            return redirect()->back()->with('error','Stok tidak cukup.');
        }

        $product->decrement('stok', $stokKeluar);

        // Catat log
        StokTokoLog::create([
            'product_id' => $product->id,
            'stok_keluar' => $stokKeluar,
            'tanggal' => now()->format('Y-m-d'),
            'keterangan' => $request->keterangan ?? 'Kurangi stok',
        ]);

        return redirect()->back()->with('success','Stok toko berhasil diperbarui.');
    }
}