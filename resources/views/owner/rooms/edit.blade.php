@extends('layouts.app')

@section('title', 'Edit Kamar - KosMonitor')
@section('page_title', 'Edit Data Kamar')

@section('content')
<div class="container-fluid p-0">

    <div class="row">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Formulir Perubahan Data Kamar</h6>
                </div>
                <div class="card-body">
                    
                    <!-- Route update menggunakan ID kamar -->
                    <form action="{{ route('owner.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Wajib untuk proses update data di Laravel -->

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="room_number" class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
                                <!-- Menggunakan old() dengan parameter kedua sebagai nilai bawaan dari database -->
                                <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                       id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" 
                                       required>
                                @error('room_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="room_type" class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('room_type') is-invalid @enderror" 
                                       id="room_type" name="room_type" value="{{ old('room_type', $room->room_type) }}" 
                                       required>
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
                                           id="monthly_price" name="monthly_price" value="{{ old('monthly_price', (int)$room->monthly_price) }}" 
                                           min="0" required>
                                    @error('monthly_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                                    <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Terisi (Occupied)</option>
                                    <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Perbaikan (Maintenance)</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Fasilitas Kamar</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto Kamar Saat Ini</label>
                            <div class="mb-2">
                                @if($room->photo)
                                    <img src="{{ asset('storage/' . $room->photo) }}" alt="Foto Kamar" class="img-thumbnail" style="max-height: 150px;">
                                @else
                                    <div class="bg-light p-3 border rounded text-muted text-center" style="max-width: 200px;">
                                        <i class="bi bi-image fs-1 d-block mb-1"></i>
                                        Tidak ada foto
                                    </div>
                                @endif
                            </div>
                            
                            <label for="photo" class="form-label mt-2">Ganti Foto <small class="text-muted">(Biarkan kosong jika tidak ingin mengubah foto)</small></label>
                            <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo" accept="image/jpeg, image/png, image/jpg">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end border-top pt-3">
                            <a href="{{ route('owner.rooms.index') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Perbarui Data
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection