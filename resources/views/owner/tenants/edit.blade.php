@extends('layouts.app')

@section('title', 'Edit Penghuni - KosMonitor')
@section('page_title', 'Edit Data Penghuni')

@section('content')
<div class="container-fluid p-0">

    <div class="row">
        <div class="col-xl-9 col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold">Formulir Perubahan Data Penghuni</h6>
                    <span class="badge bg-light text-dark border">Kode: {{ $tenant->tenant_code }}</span>
                </div>
                <div class="card-body p-4">
                    
                    @if($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif

                    <form action="{{ route('owner.tenants.update', $tenant->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- BAGIAN 1: DATA AKUN (Tabel Users) -->
                        <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Akun & Login</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $tenant->user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $tenant->user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor WhatsApp/HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $tenant->user->phone) }}" 
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="is_active" class="form-label">Status Akun Akses <span class="text-danger">*</span></label>
                                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                    <option value="1" {{ old('is_active', $tenant->user->is_active) == 1 ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                                    <option value="0" {{ old('is_active', $tenant->user->is_active) == 0 ? 'selected' : '' }}>Nonaktif (Tidak Bisa Login)</option>
                                </select>
                                <small class="text-muted">Gunakan nonaktif jika penghuni sudah pindah (untuk riwayat data).</small>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="password" class="form-label">Ubah Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" 
                                       placeholder="Ketik password baru jika ingin mengubah">
                                <small class="text-warning"><i class="bi bi-info-circle"></i> Biarkan kosong jika tidak ingin mengubah password penghuni saat ini.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- BAGIAN 2: DATA PROFIL (Tabel Tenants) -->
                        <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Informasi Profil Penghuni</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="identity_number" class="form-label">Nomor Identitas (KTP/SIM) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('identity_number') is-invalid @enderror" 
                                       id="identity_number" name="identity_number" value="{{ old('identity_number', $tenant->identity_number) }}" 
                                       required>
                                @error('identity_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="emergency_contact" class="form-label">Kontak Darurat (Keluarga) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                       id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $tenant->emergency_contact) }}" 
                                       required>
                                @error('emergency_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">Alamat Asal <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      required>{{ old('address', $tenant->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end border-top pt-4">
                            <a href="{{ route('owner.tenants.index') }}" class="btn btn-light me-2">Batal</a>
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