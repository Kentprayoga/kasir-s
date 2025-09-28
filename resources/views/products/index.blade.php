@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Produk (Stok Toko)</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">+ Tambah Produk</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
            <tr>
                <td>{{ $p->nama_barang }}</td>
                <td>Rp{{ number_format($p->harga, 0, ',', '.') }}</td>
                <td>{{ $p->stok }}</td>
                <td>
                    <a href="{{ route('products.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Yakin hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Belum ada produk</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
