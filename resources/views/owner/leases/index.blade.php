@extends('layouts.app')

@section('title', 'Data Penyewaan - KosMonitor')
@section('page_title', 'Manajemen Penyewaan Kamar')

@section('content')
<div class="container-fluid p-0">
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="m-0 fw-bold">Daftar Kontrak Penyewaan</h5>
            <a href="{{ route('owner.leases.create') }}" class="btn btn-primary">
                <i class="bi bi-journal-plus me-1"></i> Buat Penyewaan Baru
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col" width="15%">Kamar</th>
                            <th scope="col" width="20%">Penghuni</th>
                            <th scope="col" width="20%">Periode Sewa</th>
                            <th scope="col" width="15%">Harga / Bulan</th>
                            <th scope="col" width="10%">Status</th>
                            <th scope="col" width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leases as $index => $lease)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-bold">Kamar {{ $lease->room->room_number }}</span>
                                    <div class="small text-muted">{{ $lease->room->room_type }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $lease->tenant->user->name }}</div>
                                    <div class="small text-muted">{{ $lease->tenant->tenant_code }}</div>
                                </td>
                                <td>
                                    <div class="small"><i class="bi bi-calendar-event me-1"></i> Mulai: {{ \Carbon\Carbon::parse($lease->start_date)->translatedFormat('d M Y') }}</div>
                                    <div class="small text-muted">
                                        <i class="bi bi-calendar-x me-1"></i> Selesai: 
                                        {{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->translatedFormat('d M Y') : 'Tanpa Batas' }}
                                    </div>
                                </td>
                                <td class="fw-semibold text-success">
                                    Rp {{ number_format($lease->monthly_price, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($lease->status == 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($lease->status == 'completed')
                                        <span class="badge bg-secondary">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Aksi Detail (Show) -->
                                    <a href="{{ route('owner.leases.show', $lease->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail Kontrak">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <!-- Aksi Akhiri Sewa / Checkout (Hanya jika status aktif) -->
                                    @if($lease->status == 'active')
                                        <form action="{{ route('owner.leases.destroy', $lease->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mengakhiri masa sewa ini? Kamar akan otomatis dikembalikan menjadi status tersedia.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1" title="Akhiri / Checkout">
                                                <i class="bi bi-box-arrow-right"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($lease->status == 'pending')
                                    <form action="{{ route('owner.leases.approve', $lease->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui Pengajuan">
                                            <i class="bi bi-check-lg"></i> Setujui
                                        </button>
                                    </form>
                                @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada data kontrak penyewaan. Silakan buat penyewaan baru.
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