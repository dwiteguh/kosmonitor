@extends('layouts.app')

@section('title', 'Manajemen Tagihan - KosMonitor')
@section('page_title', 'Daftar Tagihan Bulanan')

@section('content')
<div class="container-fluid p-0">
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="m-0 fw-bold">Semua Tagihan Penghuni</h5>
            <a href="{{ route('owner.invoices.create') }}" class="btn btn-primary">
                <i class="bi bi-receipt me-1"></i> Buat Tagihan Baru
            </a>
        </div>

        <div class="card-body">
            <!-- Menampilkan alert sukses/error jika ada -->
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
                            <th scope="col" width="15%">No. Tagihan</th>
                            <th scope="col" width="20%">Penghuni & Kamar</th>
                            <th scope="col" width="15%">Periode</th>
                            <th scope="col" width="15%">Jumlah</th>
                            <th scope="col" width="15%">Jatuh Tempo</th>
                            <th scope="col" width="10%">Status</th>
                            <th scope="col" width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $index => $invoice)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold text-primary">{{ $invoice->invoice_number }}</td>
                                <td>
                                    <div class="fw-bold">{{ $invoice->lease->tenant->user->name }}</div>
                                    <div class="small text-muted">Kamar {{ $invoice->lease->room->room_number }}</div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($invoice->billing_period)->translatedFormat('F Y') }}</td>
                                <td class="fw-bold text-dark">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="{{ \Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->status != 'paid' ? 'text-danger fw-semibold' : '' }}">
                                        {{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d M Y') }}
                                    </span>
                                </td>
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
                                <td class="text-center">
                                    <!-- Aksi Detail (Show) -->
                                    <a href="{{ route('owner.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail & Verifikasi Pembayaran">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <!-- Aksi Hapus (Hanya jika belum lunas) -->
                                    @if($invoice->status != 'paid')
                                        <form action="{{ route('owner.invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Belum ada data tagihan yang tercatat. Silakan buat tagihan baru.
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