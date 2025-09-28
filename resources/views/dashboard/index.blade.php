@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard</h2>

    {{-- Filter --}}
    <form method="GET" action="{{ route('dashboard') }}" class="mb-3 d-flex gap-2">
        <select name="filter" id="filter" onchange="toggleRange()" class="form-select" style="width:auto;">
            <option value="daily" {{ $filter=='daily'?'selected':'' }}>Hari Ini</option>
            <option value="monthly" {{ $filter=='monthly'?'selected':'' }}>Bulanan</option>
            <option value="yearly" {{ $filter=='yearly'?'selected':'' }}>Tahunan</option>
            <option value="range" {{ $filter=='range'?'selected':'' }}>Custom Range</option>
        </select>

        <input type="date" name="start_date" id="start_date" value="{{ $start? \Carbon\Carbon::parse($start)->toDateString():'' }}" class="form-control" style="width:auto; display: {{ $filter=='range'?'block':'none' }}">
        <input type="date" name="end_date" id="end_date" value="{{ $end? \Carbon\Carbon::parse($end)->toDateString():'' }}" class="form-control" style="width:auto; display: {{ $filter=='range'?'block':'none' }}">

        <button type="submit" class="btn btn-primary">Terapkan</button>
    </form>

    {{-- Ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h5>Total Penjualan</h5>
                    <h3>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-danger">
                <div class="card-body">
                    <h5>Total Pengeluaran</h5>
                    <h3>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5>Pendapatan Bersih</h5>
                    <h3>Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="card">
        <div class="card-body">
            <h5>Grafik Penjualan, Pengeluaran, Pendapatan</h5>
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Detail Transaksi --}}
<div class="card mt-4">
    <div class="card-header">Detail Transaksi</div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No Struk</th>
                    <th>Total</th>
                    <th>Uang Dibayar</th>
                    <th>Kembalian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y H:i') }}</td>
                        <td>{{ $trx->nomor_struk }}</td>
                        <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($trx->uang_dibayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($trx->kembalian, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Detail Pengeluaran --}}
<div class="card mt-4">
    <div class="card-header">Detail Pengeluaran</div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $exp)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($exp->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $exp->keterangan }}</td>
                        <td>Rp {{ number_format($exp->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada pengeluaran</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleRange() {
        const filter = document.getElementById('filter').value;
        document.getElementById('start_date').style.display = (filter === 'range') ? 'block' : 'none';
        document.getElementById('end_date').style.display = (filter === 'range') ? 'block' : 'none';
    }

    const chartData = @json($chartData);

    const labels = chartData.map(d => d.tanggal);
    const penjualan = chartData.map(d => d.penjualan);
    const pengeluaran = chartData.map(d => d.pengeluaran);
    const pendapatan = chartData.map(d => d.pendapatan);

    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Penjualan',
                    data: penjualan,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    tension: 0.3
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaran,
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.2)',
                    tension: 0.3
                },
                {
                    label: 'Pendapatan',
                    data: pendapatan,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 255, 0, 0.2)',
                    tension: 0.3
                }
            ]
        }
    });
</script>
@endpush
