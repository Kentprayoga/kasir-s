@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Manajemen Shift</h2>

    {{-- Pesan sukses / error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Form buka shift --}}
    @if(!$shiftAktif)
        <div class="card mb-4">
            <div class="card-header">Buka Shift Baru</div>
            <div class="card-body">
                <form action="{{ route('shifts.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="modal_awal" class="form-label">Modal Awal</label>
                        <input type="number" step="0.01" name="modal_awal" id="modal_awal"
                               class="form-control @error('modal_awal') is-invalid @enderror" required>
                        @error('modal_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Buka Shift</button>
                </form>
            </div>
        </div>
    @else
        {{-- Info shift aktif --}}
        <div class="card mb-4">
            <div class="card-header">Shift Aktif</div>
            <div class="card-body">
                <p><strong>Tanggal:</strong> {{ $shiftAktif->tanggal }}</p>
                <p><strong>Jam Mulai:</strong> {{ $shiftAktif->jam_mulai }}</p>
                <p><strong>Modal Awal:</strong> Rp {{ number_format($shiftAktif->modal_awal, 0, ',', '.') }}</p>

                {{-- Tutup shift --}}
                <form action="{{ route('shifts.close', $shiftAktif->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="modal_akhir" class="form-label">Modal Akhir</label>
                        <input type="number" step="0.01" name="modal_akhir" id="modal_akhir"
                               class="form-control @error('modal_akhir') is-invalid @enderror" required>
                        @error('modal_akhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger">Tutup Shift</button>
                </form>
            </div>
        </div>
    @endif

    {{-- Daftar shift sebelumnya --}}
    <div class="card">
        <div class="card-header">Riwayat Shift</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Modal Awal</th>
                        <th>Modal Akhir</th>
                        <th>Total Penjualan</th>
                        <th>Total Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                        <tr>
                            <td>{{ $shift->tanggal }}</td>
                            <td>{{ $shift->user->name ?? '-' }}</td>
                            <td>{{ $shift->jam_mulai }}</td>
                            <td>{{ $shift->jam_selesai ?? '-' }}</td>
                            <td>Rp {{ number_format($shift->modal_awal, 0, ',', '.') }}</td>
                            <td>{{ $shift->modal_akhir ? 'Rp '.number_format($shift->modal_akhir, 0, ',', '.') : '-' }}</td>
                            <td>Rp {{ number_format($shift->total_penjualan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($shift->expenses->sum('jumlah'), 0, ',', '.') }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data shift</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
