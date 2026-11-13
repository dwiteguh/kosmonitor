@extends('layouts.app')

@section('title', 'Detail Penyewaan - KosMonitor')
@section('page_title', 'Rincian Kontrak Penyewaan')

@section('content')
<div class="container-fluid p-0">
    
    <!-- Tombol Kembali & Aksi -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('owner.leases.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penyewaan
        </a>

        @if($lease->status == 'active')
            <form action="{{ route('owner.leases.destroy', $lease->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mengakhiri masa sewa ini? Kamar akan otomatis dikembalikan menjadi status tersedia.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right me-1"></i> Akhiri Sewa (Checkout)
                </button>
            </form>
        @endif
    </div>

    <div class="row">
        <!-- Kolom Informasi Kontrak -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Informasi Kontrak</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($lease->status == 'active')
                            <span class="badge bg-success px-3 py-2 fs-6">Kontrak Aktif</span>
                        @elseif($lease->status == 'completed')
                            <span class="badge bg-secondary px-3 py-2 fs-6">Kontrak Selesai</span>
                        @else
                            <span class="badge bg-danger px-3 py-2 fs-6">Kontrak Dibatalkan</span>
                        @endif
                    </div>

                    <table class="table table-borderless table-sm align-middle">
                        <tbody>
                            <tr>
                                <td class="text-muted" width="40%">Nomor Kamar</td>
                                <td width="5%">:</td>
                                <td class="fw-bold fs-5 text-primary">Kamar {{ $lease->room->room_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tipe Kamar</td>
                                <td>:</td>
                                <td class="fw-semibold">{{ $lease->room->room_type }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Penghuni</td>
                                <td>:</td>
                                <td class="fw-bold">{{ $lease->tenant->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kode Penghuni</td>
                                <td>:</td>
                                <td>{{ $lease->tenant->tenant_code }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Mulai Sewa</td>
                                <td>:</td>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($lease->start_date)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Akhir Kontrak</td>
                                <td>:</td>
                                <td class="fw-semibold">
                                    {{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->translatedFormat('d F Y') : 'Fleksibel / Tanpa Batas' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga Kontrak</td>
                                <td>:</td>
                                <td class="text-success fw-bold">Rp {{ number_format($lease->monthly_price, 0, ',', '.') }} / bln</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Uang Deposit</td>
                                <td>:</td>
                                <td class="fw-semibold">Rp {{ number_format($lease->deposit, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Riwayat Tagihan Terkait (Invoices) -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold">Daftar Tagihan Bulanan</h6>
                    <!-- Tombol buat tagihan manual nanti bisa diletakkan di sini -->
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-3">No. Tagihan</th>
                                    <th scope="col">Periode</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Jatuh Tempo</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lease->invoices as $invoice)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-primary">{{ $invoice->invoice_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->billing_period)->translatedFormat('F Y') }}</td>
                                        <td class="fw-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d M Y') }}</td>
                                        <td>
                                            @if($invoice->status == 'paid')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($invoice->status == 'partial')
                                                <span class="badge bg-warning text-dark">Sebagian</span>
                                            @elseif($invoice->status == 'overdue')
                                                <span class="badge bg-danger">Terlambat</span>
                                            @else
                                                <span class="badge bg-secondary">Belum Bayar</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Belum ada tagihan yang dibuat untuk kontrak penyewaan ini.
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