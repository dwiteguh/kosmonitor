@extends('layouts.app')

@section('title', 'Tagihan Saya - KosMonitor')
@section('page_title', 'Daftar Tagihan Sewa Kamar')

@section('content')
<div class="container-fluid p-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="m-0 fw-bold">Tagihan Bulanan Anda</h5>
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
                            <th>No. Tagihan</th>
                            <th>Periode</th>
                            <th>Jumlah</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $invoice->invoice_number }}</td>
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
                                <td class="text-center">
                                    <a href="{{ route('tenant.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Bayar / Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada tagihan untuk Anda saat ini.
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