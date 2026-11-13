@extends('layouts.app')

@section('title', 'Manajemen Keluhan - KosMonitor')
@section('page_title', 'Daftar Keluhan Penghuni')

@section('content')
<div class="container-fluid p-0">
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="m-0 fw-bold">Semua Keluhan & Laporan Kerusakan</h5>
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
                            <th scope="col" width="15%">Kamar & Pelapor</th>
                            <th scope="col" width="30%">Judul / Subjek Keluhan</th>
                            <th scope="col" width="15%">Tanggal Lapor</th>
                            <th scope="col" width="15%">Status</th>
                            <th scope="col" width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $index => $complaint)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">Kamar {{ $complaint->room->room_number }}</div>
                                    <div class="small text-muted">{{ $complaint->tenant->user->name }}</div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $complaint->title }}</span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($complaint->created_at)->translatedFormat('d M Y') }}
                                </td>
                                <td>
                                    @if($complaint->status == 'pending')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($complaint->status == 'in_progress')
                                        <span class="badge bg-info text-dark">Diproses</span>
                                    @elseif($complaint->status == 'resolved')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Aksi Detail & Tanggapan (Show) -->
                                    <a href="{{ route('owner.complaints.show', $complaint->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail & Tanggapi">
                                        <i class="bi bi-eye me-1"></i> Detail / Tanggapi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada keluhan atau laporan kerusakan yang diajukan oleh penghuni.
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