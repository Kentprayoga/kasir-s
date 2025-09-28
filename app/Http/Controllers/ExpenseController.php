<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Shift;

class ExpenseController extends Controller
{
    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
        ]);

        // cari shift aktif
        $shift = Shift::whereNull('jam_selesai')->latest()->first();
        if (!$shift) {
            return back()->with('error', 'Tidak ada shift aktif. Harap buka shift dulu.');
        }

        Expense::create([
            'shift_id' => $shift->id,
            'tanggal' => now()->toDateString(),
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('expenses.create')->with('success', 'Pengeluaran berhasil dicatat.');
    }
}