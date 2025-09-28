@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Transaksi</h2>

    <p><strong>Nomor Struk:</strong> {{ $transaction->nomor_struk }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d/m/Y H:i') }}</p>
    <p><strong>Total:</strong> Rp{{ number_format($transaction->total,0,',','.') }}</p>
    <p><strong>Uang Dibayar:</strong> Rp{{ number_format($transaction->uang_dibayar,0,',','.') }}</p>
    <p><strong>Kembalian:</strong> Rp{{ number_format($transaction->kembalian,0,',','.') }}</p>

    <h4>Barang Dibeli</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
            <tr>
                <td>{{ $item->product->nama }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp{{ number_format($item->harga,0,',','.') }}</td>
                <td>Rp{{ number_format($item->subtotal,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
