@extends('layouts.app')

@section('title', 'Detail Tagihan - KosMonitor')
@section('page_title', 'Rincian Tagihan & Pembayaran')

@section('content')
<div class="container-fluid p-0">
    
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="{{ route('owner.invoices.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Tagihan
        </a>
    </div>

    <!-- Alert Notifikasi -->
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

    <div class="row">
        <!-- Informasi Ringkas Tagihan -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Informasi Tagihan</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="text-muted small">Nomor Tagihan</span>
                        <h4 class="fw-bold text-primary">{{ $invoice->invoice_number }}</h4>
                        
                        <div class="mt-2">
                            @if($invoice->status == 'paid')
                                <span class="badge bg-success px-3 py-2 fs-6">Lunas</span>
                            @elseif($invoice->status == 'partial')
                                <span class="badge bg-warning text-dark px-3 py-2 fs-6">Dibayar Sebagian</span>
                            @elseif($invoice->status == 'overdue')
                                <span class="badge bg-danger px-3 py-2 fs-6">Terlambat</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 fs-6">Belum Bayar</span>
                            @endif
                        </div>
                    </div>

                    <table class="table table-borderless table-sm align-middle mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted" width="40%">Penghuni</td>
                                <td width="5%">:</td>
                                <td class="fw-bold">{{ $invoice->lease->tenant->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kamar</td>
                                <td>:</td>
                                <td class="fw-semibold">Kamar {{ $invoice->lease->room->room_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Periode</td>
                                <td>:</td>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($invoice->billing_period)->translatedFormat('F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jatuh Tempo</td>
                                <td>:</td>
                                <td class="fw-semibold text-danger">{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Tagihan</td>
                                <td>:</td>
                                <td class="fw-bold text-dark fs-5">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran & Bukti Transfer -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold">Riwayat Pembayaran & Bukti Transfer</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-3">Tanggal</th>
                                    <th scope="col">Jumlah Bayar</th>
                                    <th scope="col">Metode</th>
                                    <th scope="col">Bukti</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Aksi Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoice->payments as $payment)
                                    <tr>
                                        <td class="ps-3">{{ \Carbon\Carbon::parse($payment->payment_date)->translatedFormat('d M Y') }}</td>
                                        <td class="fw-bold text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td>{{ strtoupper($payment->payment_method) }}</td>
                                        <td>
                                            @if($payment->proof_of_payment)
                                                <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat Bukti Transfer">
                                                    <i class="bi bi-image"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-muted small">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->status == 'verified')
                                                <span class="badge bg-success">Terverifikasi</span>
                                            @elseif($payment->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($payment->status == 'pending')
                                                <!-- Tombol Verifikasi Pembayaran -->
                                                <form action="{{ route('owner.payments.verify', $payment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Setujui Pembayaran">
                                                        <i class="bi bi-check-lg"></i> Setujui
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            Belum ada riwayat pembayaran yang diunggah untuk tagihan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection