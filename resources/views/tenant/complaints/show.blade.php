@extends('layouts.app')

@section('title', 'Detail Keluhan - KosMonitor')
@section('page_title', 'Rincian & Riwayat Tanggapan')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-4">
        <a href="{{ route('tenant.complaints.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3"><h6 class="m-0 fw-bold">Detail Laporan</h6></div>
                <div class="card-body">
                    <h5 class="fw-bold mb-2">{{ $complaint->title }}</h5>
                    <p class="text-muted small">Dilaporkan: {{ \Carbon\Carbon::parse($complaint->created_at)->translatedFormat('d M Y, H:i') }}</p>
                    <p class="bg-light p-3 rounded">{{ $complaint->description }}</p>
                    @if($complaint->photo)
                        <img src="{{ asset('storage/' . $complaint->photo) }}" class="img-fluid rounded border" alt="Foto">
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3"><h6 class="m-0 fw-bold">Riwayat Tanggapan Pemilik Kos</h6></div>
                <div class="card-body">
                    @forelse($complaint->updates as $up)
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold small text-primary">{{ $up->user->name }}</span>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($up->created_at)->translatedFormat('d M Y, H:i') }}</small>
                                </div>
                                <p class="mb-1 small">{{ $up->message }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">Belum ada tanggapan dari pemilik kos.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection