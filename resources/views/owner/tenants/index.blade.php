@extends('layouts.app')

@section('title', 'Manajemen Penghuni - KosMonitor')
@section('page_title', 'Manajemen Penghuni')

@section('content')
<div class="container-fluid p-0">
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="m-0 fw-bold">Daftar Penghuni</h5>
            <!-- Tombol Tambah Penghuni oleh Owner (Opsional) -->
            <a href="{{ route('owner.tenants.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Penghuni
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
                            <th>No</th>
                            <th>Kode Penghuni</th>
                            <th>Nama Lengkap</th>
                            <th>Informasi Kontak</th>
                            <th>Status Kamar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $index => $tenant)
                            @php
                                // Cek apakah tenant memiliki kontrak aktif
                                $hasActiveLease = $tenant->leases()->where('status', 'active')->exists();
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold text-primary">{{ $tenant->tenant_code }}</td>
                                <td>
                                    <div class="fw-bold">{{ $tenant->user->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $tenant->user->email ?? '-' }}</small>
                                </td>
                                <td>
                                    <div><i class="bi bi-telephone me-1 text-muted"></i> {{ $tenant->phone }}</div>
                                </td>
                                <td>
                                    @if($hasActiveLease)
                                        <span class="badge bg-success">Aktif (Berpintu)</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu Kamar</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Tombol Detail / Lihat Profil -->
                                    <a href="{{ route('owner.tenants.show', $tenant->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- Tombol Hapus (Owner TIDAK ADA Tombol Edit Data Pribadi Penghuni) -->
                                    <form action="{{ route('owner.tenants.destroy', $tenant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data penghuni ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Penghuni">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada data penghuni yang terdaftar.
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