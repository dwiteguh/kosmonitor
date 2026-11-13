@extends('layouts.app')

@section('title', 'Buat Keluhan - KosMonitor')
@section('page_title', 'Laporkan Kerusakan / Kendala')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Formulir Keluhan Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('tenant.complaints.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul / Subjek Kendala <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Contoh: AC kamar bocor / Lampu koridor mati" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Detail <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Jelaskan kendala secara lengkap..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Lampiran Foto (Opsional)</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('tenant.complaints.index') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Kirim Keluhan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection