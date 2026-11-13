@extends('layouts.app')

@section('title', 'Detail Kamar ' . $room->room_number . ' - KosMonitor')
@section('page_title', 'Detail Kamar: ' . $room->room_number)

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold">Informasi Lengkap Kamar</h6>
                    <a href="{{ route('owner.rooms.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Kolom Foto -->
                        <div class="col-md-5 mb-4 mb-md-0 text-center">
                            @if($room->photo)
                                <img src="{{ asset('storage/' . $room->photo) }}" alt="Foto Kamar {{ $room->room_number }}" class="img-fluid rounded shadow-sm" style="max-height: 350px; object-fit: cover; width: 100%;">
                            @else
                                <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center text-muted" style="height: 300px; width: 100%;">
                                    <i class="bi bi-image" style="font-size: 4rem;"></i>
                                    <span class="mt-2">Tidak ada foto tersedia</span>
                                </div>
                            @endif
                        </div>

                        <!-- Kolom Data -->
                        <div class="col-md-7">
                            <h4 class="fw-bold mb-3">Kamar {{ $room->room_number }}</h4>
                            
                            <table class="table table-borderless table-sm">
                                <tbody>
                                    <tr>
                                        <td class="text-muted" width="150">Tipe Kamar</td>
                                        <td width="20">:</td>
                                        <td class="fw-semibold">{{ $room->room_type }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Harga Sewa</td>
                                        <td>:</td>
                                        <td class="text-success fw-bold fs-5">Rp {{ number_format($room->monthly_price, 0, ',', '.') }} <small class="text-muted fs-6 fw-normal">/ bulan</small></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status</td>
                                        <td>:</td>
                                        <td>
                                            @if($room->status == 'available')
                                                <span class="badge bg-info px-3 py-2">Tersedia</span>
                                            @elseif($room->status == 'occupied')
                                                <span class="badge bg-success px-3 py-2">Terisi</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2">Perbaikan</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-4">
                                <h6 class="text-muted border-bottom pb-2">Deskripsi & Fasilitas</h6>
                                <p class="mt-2 text-dark" style="white-space: pre-line;">
                                    {{ $room->description ?: 'Tidak ada deskripsi yang ditambahkan untuk kamar ini.' }}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light p-3 d-flex justify-content-end">
                    <a href="{{ route('owner.rooms.edit', $room->id) }}" class="btn btn-primary me-2">
                        <i class="bi bi-pencil me-1"></i> Edit Data
                    </a>
                    
                    <form action="{{ route('owner.rooms.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini? Data yang dihapus tidak dapat dikembalikan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Hapus Kamar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection