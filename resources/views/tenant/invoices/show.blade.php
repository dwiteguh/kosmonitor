@extends('layouts.app')

@section('title', 'Detail Tagihan - KosMonitor')
@section('page_title', 'Rincian & Pembayaran Tagihan')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-4">
        <a href="{{ route('tenant.invoices.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Tagihan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Rincian Tagihan -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Informasi Tagihan</h6>
                </div>
                <div class="card-body">
                    <h4 class="fw-bold text-primary mb-3">{{ $invoice->invoice_number }}</h4>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Periode</td>
                            <td class="fw-semibold">: {{ \Carbon\Carbon::parse($invoice->billing_period)->translatedFormat('F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jatuh Tempo</td>
                            <td class="fw-semibold text-danger">: {{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Tagihan</td>
                            <td class="fw-bold text-success fs-5">: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>: 
                                @if($invoice->status == 'paid') <span class="badge bg-success">Lunas</span>
                                @else <span class="badge bg-warning text-dark">Belum Lunas</span> @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Upload Bukti Bayar / Riwayat -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Konfirmasi Pembayaran & Bukti Transfer</h6>
                </div>
                <div class="card-body">
                    @if($invoice->status != 'paid')
                        <form action="{{ route('tenant.invoices.pay', $invoice->id) }}" method="POST" enctype="multipart/form-data" class="mb-4 pb-3 border-bottom">
                            @csrf
                            <div class="mb-3">
                                <label for="amount" class="form-label">Jumlah Dibayar (Rp)</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ $invoice->amount }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS / E-Wallet</option>
                                    <option value="cash">Tunai</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="proof_of_payment" class="form-label">Upload Bukti Transfer (Foto/Gambar)</label>
                                <input type="file" class="form-control" id="proof_of_payment" name="proof_of_payment" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success">Tagihan ini sudah lunas. Terima kasih!</div>
                    @endif

                    <h6 class="fw-bold mb-3">Riwayat Unggahan Pembayaran</h6>
                    <ul class="list-group">
                        @forelse($invoice->payments as $pay)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">Rp {{ number_format($pay->amount, 0, ',', '.') }}</span> 
                                    <small class="text-muted">({{ \Carbon\Carbon::parse($pay->payment_date)->translatedFormat('d M Y') }})</small>
                                </div>
                                <div>
                                    @if($pay->status == 'verified') <span class="badge bg-success">Disetujui</span>
                                    @elseif($pay->status == 'rejected') <span class="badge bg-danger">Ditolak</span>
                                    @else <span class="badge bg-warning text-dark">Menunggu Verifikasi</span> @endif
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center">Belum ada bukti bayar yang diunggah.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection