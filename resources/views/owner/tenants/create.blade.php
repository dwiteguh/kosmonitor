@extends('layouts.app')

@section('title', 'Tambah Penghuni - KosMonitor')
@section('page_title', 'Tambah Penghuni Baru')

@section('content')
<div class="container-fluid p-0">

    <div class="row">
        <div class="col-xl-9 col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Formulir Pendaftaran Penghuni</h6>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Alert jika ada error sistem (dari try-catch Controller) -->
                    @if($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif

                    <form action="{{ route('owner.tenants.store') }}" method="POST">
                        @csrf

                        <!-- BAGIAN 1: DATA AKUN (Tabel Users) -->
                        <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Akun (Untuk Login)</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Nama sesuai KTP" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="email@contoh.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor WhatsApp/HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="Contoh: 08123456789" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Sementara</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" 
                                       placeholder="Kosongkan untuk password default">
                                <small class="text-muted">Jika dikosongkan, password default adalah: <strong>kosmonitor123</strong></small>
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
                                       id="identity_number" name="identity_number" value="{{ old('identity_number') }}" 
                                       placeholder="16 Digit NIK" required>
                                @error('identity_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="emergency_contact" class="form-label">Kontak Darurat (Keluarga) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                       id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}" 
                                       placeholder="Nama - No HP" required>
                                @error('emergency_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">Alamat Asal <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="Alamat lengkap sesuai identitas" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end border-top pt-4">
                            <a href="{{ route('owner.tenants.index') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan & Buat Akun
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection