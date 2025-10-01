@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Produk</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang" 
                   value="{{ old('nama_barang', $product->nama_barang) }}" required>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="text" class="form-control" id="harga" name="harga" 
                value="{{ old('harga', number_format($product->harga, 0, )) }}" required>
        </div>


        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" 
                   value="{{ old('stok', $product->stok) }}" required>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>


@endsection
