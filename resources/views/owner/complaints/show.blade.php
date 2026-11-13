@extends('layouts.app')

@section('title', 'Detail Keluhan - KosMonitor')
@section('page_title', 'Rincian & Tanggapan Keluhan')

@section('content')
<div class="container-fluid p-0">
    
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="{{ route('owner.complaints.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Keluhan
        </a>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Kolom Informasi & Foto Kerusakan -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Informasi Laporan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted small">Status Saat Ini:</span>
                        <div>
                            @if($complaint->status == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2 fs-6">Menunggu</span>
                            @elseif($complaint->status == 'in_progress')
                                <span class="badge bg-info text-dark px-3 py-2 fs-6">Sedang Diproses</span>
                            @elseif($complaint->status == 'resolved')
                                <span class="badge bg-success px-3 py-2 fs-6">Selesai</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 fs-6">Ditolak</span>
                            @endif
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">{{ $complaint->title }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-clock me-1"></i> Dilaporkan pada {{ \Carbon\Carbon::parse($complaint->created_at)->translatedFormat('d F Y, H:i') }}
                    </p>

                    <div class="bg-light p-3 rounded mb-3">
                        <div class="small text-muted mb-1">Pelapor & Kamar:</div>
                        <div class="fw-bold">{{ $complaint->tenant->user->name }} (Kamar {{ $complaint->room->room_number }})</div>
                    </div>

                    <div class="mb-3">
                        <div class="small text-muted mb-1">Deskripsi Kendala:</div>
                        <p class="text-dark bg-white p-3 border rounded" style="white-space: pre-line;">{{ $complaint->description }}</p>
                    </div>

                    <!-- Bukti Foto Keluhan (Jika Ada) -->
                    @if($complaint->photo)
                        <div class="mb-3">
                            <div class="small text-muted mb-1">Lampiran Foto Kerusakan:</div>
                            <a href="{{ asset('storage/' . $complaint->photo) }}" target="_blank">
                                <img src="{{ asset('storage/' . $complaint->photo) }}" alt="Foto Kerusakan" class="img-fluid rounded border" style="max-height: 250px; object-fit: cover; width: 100%;">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Riwayat Tanggapan & Form Balasan -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Riwayat Tanggapan & Perbarui Status</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    
                    <!-- Daftar Kronologi Pesan / Updates -->
                    <div class="mb-4" style="max-height: 400px; overflow-y: auto;">
                        @forelse($complaint->updates as $update)
                            <div class="card mb-3 border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold small text-primary">{{ $update->user->name }}</span>
                                        <span class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($update->created_at)->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                    <p class="mb-1 text-dark small" style="white-space: pre-line;">{{ $update->message }}</p>
                                    <div>
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">Status: {{ strtoupper($update->status) }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">
                                Belum ada tanggapan atau pembaruan status yang dicatat untuk keluhan ini.
                            </div>
                        @endforelse
                    </div>

                    <!-- Form Input Tanggapan Baru -->
                    <div class="border-top pt-3">
                        <form action="{{ route('owner.complaints.updateStatus', $complaint->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="status" class="form-label small fw-bold">Ubah Status Penanganan <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                                    <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>Sedang Diproses (In Progress)</option>
                                    <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Selesai (Resolved)</option>
                                    <option value="rejected" {{ $complaint->status == 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label small fw-bold">Pesan Tanggapan / Catatan <span class="text-danger">*</span></label>
                                <textarea class="form-control form-control-sm @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="3" 
                                          placeholder="Tuliskan tanggapan, instruksi teknisi, atau informasi perbaikan untuk penghuni..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-send me-1"></i> Kirim Tanggapan & Update Status
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection