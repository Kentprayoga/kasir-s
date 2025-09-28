<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Expense;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'daily'); 
        $start = $request->get('start_date');
        $end   = $request->get('end_date');

        $now = Carbon::now();

        // Kalau user pilih custom range
        if ($filter === 'range' && $start && $end) {
            $start = Carbon::parse($start)->startOfDay();
            $end   = Carbon::parse($end)->endOfDay();
        } elseif ($filter === 'daily') {
            $start = $now->copy()->startOfDay();
            $end   = $now->copy()->endOfDay();
        } elseif ($filter === 'monthly') {
            $start = $now->copy()->startOfMonth();
            $end   = $now->copy()->endOfMonth();
        } elseif ($filter === 'yearly') {
            $start = $now->copy()->startOfYear();
            $end   = $now->copy()->endOfYear();
        }

        // Total penjualan
        $totalPenjualan = Transaction::whereBetween('tanggal_transaksi', [$start, $end])
            ->sum('total');

        // Total pengeluaran
        $totalPengeluaran = Expense::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->sum('jumlah');

        // Pendapatan bersih
        $pendapatan = $totalPenjualan - $totalPengeluaran;

        // Data untuk chart
        $chartPenjualan = Transaction::select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('SUM(total) as total')
            )
            ->whereBetween('tanggal_transaksi', [$start, $end])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $chartPengeluaran = Expense::select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(jumlah) as total')
            )
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        // Satukan tanggal
        $dates = collect();
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->copy()->addDay());
        foreach ($period as $date) {
            $dates->push($date->format('Y-m-d'));
        }

        $chartData = $dates->map(function ($date) use ($chartPenjualan, $chartPengeluaran) {
            $penjualan = $chartPenjualan[$date] ?? 0;
            $pengeluaran = $chartPengeluaran[$date] ?? 0;
            return [
                'tanggal' => $date,
                'penjualan' => $penjualan,
                'pengeluaran' => $pengeluaran,
                'pendapatan' => $penjualan - $pengeluaran,
            ];
        });

// di akhir method index, sebelum return view

        $transactions = Transaction::whereBetween('tanggal_transaksi', [$start, $end])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $expenses = Expense::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('dashboard.index', compact(
            'filter', 'totalPenjualan', 'totalPengeluaran', 'pendapatan',
            'chartData', 'start', 'end', 'transactions', 'expenses'
        ));

    }
}