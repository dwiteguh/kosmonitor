@extends('layouts.app')

@section('title', 'Data Kamar - KosMonitor')
@section('page_title', 'Manajemen Kamar')

@section('content')
<div class="container-fluid p-0">
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="m-0 fw-bold">Daftar Kamar</h5>
            <a href="{{ route('owner.rooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kamar
            </a>
        </div>

        <div class="card-body">
            <!-- Menampilkan alert sukses jika ada -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col" width="10%">Foto</th>
                            <th scope="col" width="15%">No. Kamar</th>
                            <th scope="col" width="15%">Tipe</th>
                            <th scope="col" width="20%">Harga Sewa</th>
                            <th scope="col" width="15%">Status</th>
                            <th scope="col" width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $index => $room)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($room->photo)
                                        <img src="{{ asset('storage/' . $room->photo) }}" alt="Foto Kamar" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center text-muted img-thumbnail" style="width: 50px; height: 50px;">
                                            <i class="bi bi-camera"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $room->room_number }}</td>
                                <td>{{ $room->room_type }}</td>
                                <td>Rp {{ number_format($room->monthly_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($room->status == 'available')
                                        <span class="badge bg-info">Tersedia</span>
                                    @elseif($room->status == 'occupied')
                                        <span class="badge bg-success">Terisi</span>
                                    @else
                                        <span class="badge bg-secondary">Perbaikan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Aksi Detail (Show) -->
                                    <a href="{{ route('owner.rooms.show', $room->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <!-- Aksi Edit -->
                                    <a href="{{ route('owner.rooms.edit', $room->id) }}" class="btn btn-sm btn-outline-primary mx-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <!-- Aksi Hapus (Perlu Form dan Konfirmasi) -->
                                    <form action="{{ route('owner.rooms.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini? Data yang dihapus tidak dapat dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada data kamar. Silakan tambahkan kamar baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection