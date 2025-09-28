@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Catat Pengeluaran</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan"
                   class="form-control @error('keterangan') is-invalid @enderror" required>
            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" step="0.01" name="jumlah" id="jumlah"
                   class="form-control @error('jumlah') is-invalid @enderror" required>
            @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
