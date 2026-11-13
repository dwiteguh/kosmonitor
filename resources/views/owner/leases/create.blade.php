@extends('layouts.app')

@section('title', 'Buat Penyewaan - KosMonitor')
@section('page_title', 'Buat Kontrak Penyewaan Baru')

@section('content')
<div class="container-fluid p-0">

    <div class="row">
        <div class="col-xl-9 col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Formulir Penyewaan Kamar</h6>
                </div>
                <div class="card-body p-4">
                    
                    @if($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif

                    <form action="{{ route('owner.leases.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <!-- Pilih Penghuni -->
                            <div class="col-md-6">
                                <label for="tenant_id" class="form-label">Pilih Penghuni <span class="text-danger">*</span></label>
                                <select class="form-select @error('tenant_id') is-invalid @enderror" id="tenant_id" name="tenant_id" required>
                                    <option value="" disabled selected>-- Pilih Penghuni Terdaftar --</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->user->name }} ({{ $tenant->tenant_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tenant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pilih Kamar (Hanya yang statusnya available) -->
                            <div class="col-md-6">
                                <label for="room_id" class="form-label">Pilih Kamar Kosong <span class="text-danger">*</span></label>
                                <select class="form-select @error('room_id') is-invalid @enderror" id="room_id" name="room_id" required>
                                    <option value="" disabled selected>-- Pilih Kamar Tersedia --</option>
                                    @foreach($rooms as $room)
                                        <!-- Kita simpan harga sewa pada atribut data-price agar bisa dibaca oleh JavaScript -->
                                        <option value="{{ $room->id }}" data-price="{{ $room->monthly_price }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            Kamar {{ $room->room_number }} - {{ $room->room_type }} (Rp {{ number_format($room->monthly_price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Tanggal Mulai Sewa -->
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Tanggal Mulai Sewa <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Berakhir Sewa -->
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Tanggal Berakhir Kontrak <small class="text-muted">(Opsional)</small></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                <small class="text-muted">Kosongkan jika kontrak fleksibel / tanpa batas akhir tetap.</small>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <!-- Harga Sewa Bulanan -->
                            <div class="col-md-6">
                                <label for="monthly_price" class="form-label">Harga Kontrak / Bulan (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('monthly_price') is-invalid @enderror" 
                                           id="monthly_price" name="monthly_price" value="{{ old('monthly_price') }}" 
                                           placeholder="Otomatis terisi dari data kamar" min="0" required>
                                    @error('monthly_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Uang Jaminan / Deposit -->
                            <div class="col-md-6">
                                <label for="deposit" class="form-label">Uang Jaminan / Deposit (Rp) <small class="text-muted">(Opsional)</small></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('deposit') is-invalid @enderror" 
                                           id="deposit" name="deposit" value="{{ old('deposit', 0) }}" 
                                           min="0">
                                    @error('deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end border-top pt-4">
                            <a href="{{ route('owner.leases.index') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Kontrak & Ubah Status Kamar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- Skrip JavaScript pendukung untuk mengisi harga otomatis -->
<script>
    document.getElementById('room_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var price = selectedOption.getAttribute('data-price');
        
        if (price) {
            document.getElementById('monthly_price').value = price;
        }
    });
</script>
@endsection