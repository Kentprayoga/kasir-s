<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
public function index(Request $request)
{
    $query = Transaction::with('items.product');

    // Jika filter = semua -> tampilkan semua
    if ($request->filter === 'semua') {
        // Tidak ada filter tanggal
    } else {
        // Default = hari ini
        $tanggal = $request->tanggal ?? now()->toDateString();
        $query->whereDate('tanggal_transaksi', $tanggal);
    }

    $transactions = $query->latest()->paginate(10);

    return view('transactions.index', compact('transactions'));
}


    public function create()
    {
        $products = Product::all();
        return view('transactions.create', compact('products'));
    }

public function store(Request $request)
{
    $request->validate([
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.qty' => 'required|integer|min:1',
        'uang_dibayar' => 'required',
    ]);

    DB::beginTransaction();
    try {
        $total = 0;

        $shift = \App\Models\Shift::whereNull('jam_selesai')->latest()->first();
        if (!$shift) {
            return back()->with('error', 'Tidak ada shift aktif. Harap buka shift terlebih dahulu!');
        }

        $today = Carbon::now()->format('Ymd');
        $lastTransaction = Transaction::whereDate('tanggal_transaksi', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();
        $nextNumber = $lastTransaction ? intval(substr($lastTransaction->nomor_struk, -4)) + 1 : 1;
        $nomorStruk = "TRX-{$today}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 🔹 convert uang dibayar dari "8.000" → 8000
        $uangDibayar = (int) str_replace('.', '', $request->uang_dibayar);

        $transaction = Transaction::create([
            'nomor_struk' => $nomorStruk,
            'shift_id' => $shift->id,
            'tanggal_transaksi' => now(),
            'total' => 0,
            'uang_dibayar' => $uangDibayar,
            'kembalian' => 0,
        ]);

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);

            $harga = $product->harga;
            $subtotal = $harga * $item['qty'];
            $total += $subtotal;

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'qty' => $item['qty'],
                'harga' => $harga,
                'subtotal' => $subtotal,
            ]);

            $product->decrement('stok', $item['qty']);
        }

        $transaction->update([
            'total' => $total,
            'kembalian' => $uangDibayar - $total,
        ]);

        DB::commit();

        return redirect()->route('kasir.create')
            ->with('success', 'Transaksi berhasil disimpan!')
            ->with('print_id', $transaction->id);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}




public function receipt($id)
{
    $transaction = Transaction::with('items.product')->findOrFail($id);
    return view('transactions.receipt', compact('transaction'));
}

public function show(Transaction $transaction)
{
    $transaction->load('items.product');
    return view('transactions.show', compact('transaction'));
}
}