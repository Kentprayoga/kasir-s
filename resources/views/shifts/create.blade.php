@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Buka Shift Baru</h2>

    <form action="{{ route('shifts.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="modal_awal">Modal Awal</label>
            <select name="modal_awal" id="modal_awal" class="form-control" required>
                <option value="">-- Pilih Modal Awal --</option>
                <option value="50000">Rp 50.000</option>
                <option value="100000">Rp 100.000</option>
                <option value="200000">Rp 200.000</option>
                <option value="500000">Rp 500.000</option>
                <option value="1000000">Rp 1.000.000</option>
            </select>
            @error('modal_awal')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-2">Buka Shift</button>
        <a href="{{ route('shifts.index') }}" class="btn btn-secondary mt-2">Batal</a>
    </form>
</div>
@endsection
