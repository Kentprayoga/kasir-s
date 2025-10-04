@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Riwayat Transaksi</h1>

    {{-- 🔹 Form Filter --}}
    <form method="GET" action="{{ route('transactions.index') }}" class="mb-3 d-flex align-items-end gap-2">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control"
                   value="{{ request('tanggal', now()->toDateString()) }}">
        </div>

        <div class="form-group">
            <label for="filter">Filter</label>
            <select name="filter" id="filter" class="form-control">
                <option value="" {{ request('filter') == '' ? 'selected' : '' }}>Hari ini</option>
                <option value="semua" {{ request('filter') == 'semua' ? 'selected' : '' }}>Semua</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Terapkan</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Reset</a>
    </form>

    {{-- 🔹 Tabel Transaksi --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor Struk</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Uang Dibayar</th>
                <th>Kembalian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                <tr>
                    <td>{{ $t->nomor_struk }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y H:i') }}</td>
                    <td>Rp{{ number_format($t->total,0,',','.') }}</td>
                    <td>Rp{{ number_format($t->uang_dibayar,0,',','.') }}</td>
                    <td>Rp{{ number_format($t->kembalian,0,',','.') }}</td>
                    <td>
                        <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('transactions.receipt', $t->id) }}" class="btn btn-info btn-sm">Nota</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 🔹 Pagination --}}
    <div class="mt-3">
        {{ $transactions->appends(request()->query())->links() }}
    </div>
</div>
@endsection
