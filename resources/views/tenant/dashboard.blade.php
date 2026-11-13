@extends('layouts.app')

@section('title', 'Dashboard Penghuni - KosMonitor')
@section('page_title', 'Dashboard Penghuni')

@section('content')
<div class="container-fluid p-0">
    <div class="alert alert-success shadow-sm border-0 mb-4">
        <h4 class="alert-heading fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h4>
        <p class="mb-0">Berikut adalah ringkasan informasi kamar dan status sewa Anda di KosMonitor.</p>
    </div>

    <div class="row">
        <!-- Informasi Kamar Aktif -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold"><i class="bi bi-door-open text-primary me-2"></i> Informasi Kamar Anda</h6>
                </div>
                <div class="card-body">
                    @if(isset($activeLease) && $activeLease)
                        <!-- Tampilkan rincian kamar jika sudah di-acc owner -->
                        <table class="table table-borderless table-sm align-middle mb-0">
                            <tr>
                                <td class="text-muted" width="40%">Nomor Kamar</td>
                                <td width="5%">:</td>
                                <td class="fw-bold text-primary fs-5">Kamar {{ $activeLease->room->room_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tipe Kamar</td>
                                <td>:</td>
                                <td class="fw-semibold">{{ $activeLease->room->room_type }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Mulai Sewa</td>
                                <td>:</td>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($activeLease->start_date)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga Bulanan</td>
                                <td>:</td>
                                <td class="text-success fw-bold">Rp {{ number_format($activeLease->monthly_price, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    @else
                        <!-- Jika belum di-acc / belum dapat kamar -->
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-hourglass-split fs-2 text-warning d-block mb-2"></i>
                            <h6 class="fw-bold text-dark">Menunggu Persetujuan Pemilik Kos</h6>
                            <p class="mb-0 small">Akun Anda sudah aktif. Silakan hubungi pemilik kos agar segera memilihkan kamar untuk Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ringkasan Keuangan & Keluhan -->
        <div class="col-lg-6 mb-4">
            <div class="row">
                <!-- Kotak Tagihan Belum Lunas -->
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="bi bi-wallet2 text-warning fs-1 mb-2 d-block"></i>
                            <h6 class="text-muted">Tagihan Belum Lunas</h6>
                            <h3 class="fw-bold text-dark">{{ $unpaidInvoicesCount ?? 0 }}</h3>
                            <a href="{{ route('tenant.invoices.index') }}" class="btn btn-sm btn-outline-primary mt-2">Cek Tagihan</a>
                        </div>
                    </div>
                </div>

                <!-- Kotak Keluhan Aktif -->
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="bi bi-chat-square-text text-info fs-1 mb-2 d-block"></i>
                            <h6 class="text-muted">Keluhan Diproses</h6>
                            <h3 class="fw-bold text-dark">{{ $activeComplaintsCount ?? 0 }}</h3>
                            <a href="{{ route('tenant.complaints.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat Keluhan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection