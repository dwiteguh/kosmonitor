@extends('layouts.app')

@section('title', 'Dashboard Pemilik - KosMonitor')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<div class="container-fluid p-0">
    
    <div class="row mb-4">
        <!-- Card Kamar -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-primary text-white h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase fw-normal mb-1">Total Kamar</h6>
                        <h2 class="m-0 fw-bold">{{ $totalRooms ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-door-open" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>

        <!-- Card Penghuni Aktif -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-success text-white h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase fw-normal mb-1">Penghuni Aktif</h6>
                        <h2 class="m-0 fw-bold">{{ $activeTenants ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>

        <!-- Card Kamar Kosong -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-info text-white h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase fw-normal mb-1">Kamar Kosong</h6>
                        <h2 class="m-0 fw-bold">{{ $emptyRooms ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-house-door" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>

        <!-- Card Keluhan Masuk (Diperbaiki) -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-danger text-white h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase fw-normal mb-1">Keluhan Baru</h6>
                        <h2 class="m-0 fw-bold">{{ $newComplaintsCount ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Statistik Keuangan Bulan Ini -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3">
                    Keuangan Bulan Ini ({{ \Carbon\Carbon::now()->translatedFormat('F Y') }})
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Total Pendapatan</span>
                        <strong class="text-success fs-5">Rp {{ number_format($thisMonthIncome ?? 0, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Total Pengeluaran</span>
                        <strong class="text-danger fs-5">Rp {{ number_format($thisMonthExpense ?? 0, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status Tagihan (Belum Lunas/Parsial)</span>
                        <strong class="text-warning fs-5">{{ $unpaidInvoices ?? 0 }} Tagihan</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Status Kamar -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3">
                    Informasi Status Kamar
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Kamar Terisi (Occupied)
                            <span class="badge bg-success rounded-pill">{{ $occupiedRooms ?? 0 }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Kamar Kosong (Available)
                            <span class="badge bg-info rounded-pill">{{ $emptyRooms ?? 0 }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection