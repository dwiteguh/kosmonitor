@extends('layouts.app')

@section('title', 'Tambah Kamar - KosMonitor')
@section('page_title', 'Tambah Kamar Baru')

@section('content')
<div class="container-fluid p-0">

    <div class="row">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Formulir Data Kamar</h6>
                </div>
                <div class="card-body">
                    
                    <!-- enctype="multipart/form-data" wajib digunakan agar bisa upload file foto -->
                    <form action="{{ route('owner.rooms.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="room_number" class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                       id="room_number" name="room_number" value="{{ old('room_number') }}" 
                                       placeholder="Contoh: A01" required>
                                @error('room_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="room_type" class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('room_type') is-invalid @enderror" 
                                       id="room_type" name="room_type" value="{{ old('room_type') }}" 
                                       placeholder="Contoh: VIP, Standar" required>
                                @error('room_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="monthly_price" class="form-label">Harga Sewa Bulanan (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('monthly_price') is-invalid @enderror" 
                                           id="monthly_price" name="monthly_price" value="{{ old('monthly_price') }}" 
                                           placeholder="Contoh: 1500000" min="0" required>
                                    @error('monthly_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status Awal <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="" disabled {{ old('status') ? '' : 'selected' }}>Pilih Status</option>
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Perbaikan (Maintenance)</option>
                                    <!-- Status 'occupied' (terisi) biasanya diatur otomatis saat ada penyewaan -->
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Fasilitas Kamar</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Contoh: Kamar ini dilengkapi dengan AC, Kasur Springbed, Lemari Pakaian, dan Kamar Mandi Dalam.">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="photo" class="form-label">Foto Kamar <small class="text-muted">(Opsional, Maks 2MB)</small></label>
                            <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo" accept="image/jpeg, image/png, image/jpg">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end border-top pt-3">
                            <a href="{{ route('owner.rooms.index') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Data
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection