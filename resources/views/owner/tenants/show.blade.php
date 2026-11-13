@extends('layouts.app')

@section('title', 'Detail Penghuni - KosMonitor')
@section('page_title', 'Profil Lengkap Penghuni')

@section('content')
<div class="container-fluid p-0">
    
    <div class="mb-4">
        <a href="{{ route('owner.tenants.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penghuni
        </a>
    </div>

    <div class="row">
        <!-- Kolom Kiri: Ringkasan Profil -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-secondary" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $tenant->user->name ?? '-' }}</h4>
                    <p class="text-muted small mb-3">{{ $tenant->tenant_code }}</p>

                    <div class="mb-3">
                        @php
                            $hasActiveLease = $tenant->leases()->where('status', 'active')->exists();
                        @endphp
                        
                        @if($hasActiveLease)
                            <span class="badge bg-success px-3 py-2">Kamar Disetujui (Aktif)</span>
                        @else
                            <span class="badge bg-warning text-dark px-3 py-2">Menunggu Penempatan Kamar</span>
                        @endif
                    </div>

                    <!-- TOMBOL EDIT PROFIL DIHAPUS SESUAI PERMINTAAN -->
                    
                    @if($tenant->phone)
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $tenant->phone)) }}" target="_blank" class="btn btn-outline-success w-100 btn-sm">
                            <i class="bi bi-whatsapp me-1"></i> Hubungi via WA
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Informasi Lengkap -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Informasi Lengkap</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary fw-bold mb-3">Data Akun (Login)</h6>
                    <table class="table table-borderless table-sm mb-4">
                        <tr>
                            <td class="text-muted" width="30%">Email</td>
                            <td width="5%">:</td>
                            <td class="fw-semibold">{{ $tenant->user->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nomor HP/WA</td>
                            <td>:</td>
                            <td class="fw-semibold">{{ $tenant->phone }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terdaftar Sejak</td>
                            <td>:</td>
                            <td class="fw-semibold">{{ \Carbon\Carbon::parse($tenant->created_at)->translatedFormat('d F Y') }}</td>
                        </tr>
                    </table>

                    <h6 class="text-primary fw-bold mb-3">Data Identitas Pribadi</h6>
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" width="30%">Nomor Identitas (NIK)</td>
                            <td width="5%">:</td>
                            <td class="fw-semibold">{{ $tenant->identity_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat Asal</td>
                            <td>:</td>
                            <td class="fw-semibold">{{ $tenant->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection