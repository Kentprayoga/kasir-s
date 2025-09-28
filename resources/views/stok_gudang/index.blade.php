@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Stok Gudang</h1>

    {{-- Form Tambah Barang --}}
    <div class="card mb-4">
        <div class="card-header">Tambah Stok Gudang</div>
        <div class="card-body">
            <form action="{{ route('stok-gudang.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Harga</label>
                        <input type="number" step="0.01" name="harga" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Stok Awal</label>
                        <input type="number" name="stok_tersedia" class="form-control" required>
                    </div>
                    <div class="form-group col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary btn-block">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Stok Gudang --}}
    <div class="card mb-4">
        <div class="card-header">Daftar Stok Gudang</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok Tersedia</th>
                        <th>Log</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stokGudangs as $stok)
                    <tr>
                        <td>{{ $stok->nama_barang }}</td>
                        <td>Rp{{ number_format($stok->harga,0,',','.') }}</td>
                        <td>{{ $stok->stok_tersedia }}</td>
                        <td>
                            <ul>
                                @foreach($stok->logs as $log)
                                <li>{{ $log->tanggal }}: +{{ $log->stok_masuk }} / -{{ $log->stok_keluar }} ({{ $log->keterangan }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            {{-- Form update --}}
                            <form action="{{ route('stok-gudang.update', $stok->id) }}" method="POST" class="d-inline">
                                @csrf
                                <div class="input-group">
                                    <input type="number" name="stok_tersedia" value="{{ $stok->stok_tersedia }}" class="form-control" style="width:80px;">
                                    <input type="number" step="0.01" name="harga" value="{{ $stok->harga }}" class="form-control" style="width:100px;">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                                    </div>
                                </div>
                            </form>
                            {{-- Hapus --}}
                            <form action="{{ route('stok-gudang.destroy', $stok->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mt-1">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($stokGudangs->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
