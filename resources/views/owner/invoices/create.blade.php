@extends('layouts.app')

@section('title', 'Buat Tagihan - KosMonitor')
@section('page_title', 'Buat Tagihan Bulanan Baru')

@section('content')
<div class="container-fluid p-0">

    <div class="row">
        <div class="col-xl-9 col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Formulir Pembuatan Tagihan</h6>
                </div>
                <div class="card-body p-4">
                    
                    @if($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif

                    <form action="{{ route('owner.invoices.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="lease_id" class="form-label">Pilih Kontrak / Penghuni & Kamar <span class="text-danger">*</span></label>
                            <select class="form-select @error('lease_id') is-invalid @enderror" id="lease_id" name="lease_id" required>
                                <option value="" disabled selected>-- Pilih Kontrak Aktif --</option>
                                @foreach($activeLeases as $lease)
                                    <!-- Menyimpan harga sewa bulanan pada atribut data-price -->
                                    <option value="{{ $lease->id }}" data-price="{{ $lease->monthly_price }}" {{ old('lease_id') == $lease->id ? 'selected' : '' }}>
                                        Kamar {{ $lease->room->room_number }} - {{ $lease->tenant->user->name }} (Rp {{ number_format($lease->monthly_price, 0, ',', '.') }}/bln)
                                    </option>
                                @endforeach
                            </select>
                            @error('lease_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="billing_period" class="form-label">Periode Bulan Tagihan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('billing_period') is-invalid @enderror" 
                                       id="billing_period" name="billing_period" value="{{ old('billing_period', date('Y-m-01')) }}" required>
                                <small class="text-muted">Sistem akan otomatis menormalisasi ke awal bulan (Tanggal 1).</small>
                                @error('billing_period')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="due_date" class="form-label">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-10')) }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="form-label">Jumlah Tagihan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount') }}" 
                                       placeholder="Otomatis terisi dari harga kontrak" min="0" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @error('billing_period')
                                <!-- Menampilkan error duplikat tagihan jika ada -->
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end border-top pt-4">
                            <a href="{{ route('owner.invoices.index') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Tagihan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- Skrip JavaScript untuk mengisi jumlah tagihan otomatis berdasarkan kontrak -->
<script>
    document.getElementById('lease_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var price = selectedOption.getAttribute('data-price');
        
        if (price) {
            document.getElementById('amount').value = price;
        }
    });
</script>
@endsection