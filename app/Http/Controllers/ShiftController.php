<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
public function index()
{
    // Semua shift untuk tabel riwayat
    $shifts = Shift::with('user')->orderByDesc('tanggal')->get();

    // Shift aktif untuk user saat ini (atau global jika tidak pakai auth)
    if (auth()->check()) {
        $shiftAktif = Shift::where('user_id', auth()->id())
                           ->whereNull('jam_selesai')
                           ->latest()
                           ->first();
    } else {
        // fallback: ambil shift aktif paling terakhir (jika aplikasi tidak memakai login)
        $shiftAktif = Shift::whereNull('jam_selesai')->latest()->first();
    }

    return view('shifts.index', compact('shifts', 'shiftAktif'));
}

    public function create()
    {
        return view('shifts.create');
    }

    // app/Http/Controllers/ShiftController.php

public function close(Request $request, Shift $shift)
{
    $request->validate([
        'modal_akhir' => 'required|numeric|min:0',
    ]);

    // hitung total penjualan hanya transaksi di shift ini
    $totalPenjualan = $shift->transactions()->sum('total');

    $shift->update([
        'jam_selesai' => now()->format('H:i:s'),
        'modal_akhir' => $request->modal_akhir,
        'total_penjualan' => $totalPenjualan,
    ]);

    return redirect()->route('shifts.index')->with('success', 'Shift berhasil ditutup.');
}



public function store(Request $request)
{
    $request->validate([
        'modal_awal' => 'required|numeric|min:0',
    ]);

    // cek apakah user masih punya shift aktif
    $existingShift = Shift::where('user_id', auth()->id())
        ->whereNull('jam_selesai')
        ->first();

    if ($existingShift) {
        return redirect()->route('shifts.index')
            ->with('error', 'Anda masih memiliki shift yang belum ditutup!');
    }

    // simpan shift baru
    Shift::create([
        'tanggal' => now()->toDateString(),
        'jam_mulai' => now()->toTimeString(),
        'modal_awal' => $request->modal_awal,
        'user_id' => auth()->id(),
    ]);

    return redirect()->route('shifts.index')->with('success', 'Shift berhasil dibuka!');
}


    // public function close($id)
    // {
    //     $shift = Shift::findOrFail($id);

    //     $shift->update([
    //         'jam_selesai' => now(),
    //         'modal_akhir' => $shift->modal_awal + $shift->total_penjualan,
    //     ]);

    //     return redirect()->route('shifts.index')->with('success', 'Shift ditutup');
    // }
}