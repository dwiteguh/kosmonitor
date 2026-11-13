@extends('layouts.app')

@section('title', 'Keluhan Saya - KosMonitor')
@section('page_title', 'Daftar Keluhan & Laporan Kerusakan')

@section('content')
<div class="container-fluid p-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold">Riwayat Keluhan Anda</h5>
            <a href="{{ route('tenant.complaints.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Buat Keluhan Baru
            </a>
        </div>
        <div class="card-body">
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
                            <th>Tanggal</th>
                            <th>Judul Kendala</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $c)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($c->created_at)->translatedFormat('d M Y') }}</td>
                                <td class="fw-bold">{{ $c->title }}</td>
                                <td>
                                    @if($c->status == 'pending') <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($c->status == 'in_progress') <span class="badge bg-info text-dark">Diproses</span>
                                    @elseif($c->status == 'resolved') <span class="badge bg-success">Selesai</span>
                                    @else <span class="badge bg-danger">Ditolak</span> @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('tenant.complaints.show', $c->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail / Chat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada keluhan yang Anda ajukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection