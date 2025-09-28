@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Stok Toko</h1>

    {{-- Form Masukkan Stok Toko --}}
    <div class="card mb-4">
        <div class="card-header">Tambah Stok Toko</div>
        <div class="card-body">
            <form action="{{ route('stok-toko.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label>Produk</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Stok Masuk</label>
                        <input type="number" name="stok_masuk" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
                    </div>
                    <div class="form-group col-md-1 align-self-end">
                        <button type="submit" class="btn btn-primary btn-block">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Stok Toko --}}
    <div class="card mb-4">
        <div class="card-header">Daftar Stok Toko</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Stok Sekarang</th>
                        <th>Log</th>
                        <th>Kurangi Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->nama_barang }}</td>
                        <td>{{ $product->stok }}</td>
                        <td>
                            <ul>
                                @foreach($product->stokTokoLogs as $log)
                                <li>{{ $log->tanggal }}: +{{ $log->stok_masuk }} / -{{ $log->stok_keluar }} ({{ $log->keterangan }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            {{-- Form kurangi stok --}}
                            <form action="{{ route('stok-toko.update', $product->id) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="number" name="stok_keluar" class="form-control" min="1" max="{{ $product->stok }}">
                                    <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-danger btn-sm">Kurangi</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($products->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
