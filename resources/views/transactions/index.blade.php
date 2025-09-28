@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Riwayat Transaksi</h1>

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
</div>
@endsection
