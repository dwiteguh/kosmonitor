<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Penghuni - KosMonitor</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }
        .card-register {
            width: 100%;
            max-width: 750px; /* Lebar optimal untuk menampung pilihan kamar berbentuk grid */
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        .room-card {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .room-card:hover {
            border-color: #0d6efd !important;
            box-shadow: 0 0.25rem 0.75rem rgba(13, 110, 253, 0.15);
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center">
        <div class="card card-register p-4 p-md-5 bg-white">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">KosMonitor</h3>
                <p class="text-muted small mb-0">Silakan lengkapi data diri dan pilih kamar kos Anda</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('register.process') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label small fw-bold">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label small fw-bold">Nomor Telepon / WhatsApp</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                </div>

                <!-- Bagian Pemilihan Kamar -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-primary mb-2">
                        <i class="bi bi-door-open me-1"></i> Pilih Kamar Kos yang Tersedia
                    </label>
                    
                    <div class="row g-3">
                        @forelse($availableRooms ?? [] as $room)
                            <div class="col-md-6">
                                <div class="card h-100 border shadow-sm room-card">
                                    @if(isset($room->photo) && $room->photo)
                                        <img src="{{ asset('storage/' . $room->photo) }}" class="card-img-top" alt="Kamar {{ $room->room_number }}" style="height: 120px; object-fit: cover;">
                                    @else
                                        <div class="bg-light text-center py-3 text-muted">
                                            <i class="bi bi-image fs-3"></i>
                                            <p class="small mb-0 text-muted" style="font-size: 11px;">Tanpa Foto</p>
                                        </div>
                                    @endif

                                    <div class="card-body p-3 d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="fw-bold m-0">Kamar {{ $room->room_number }}</h6>
                                            <span class="badge bg-primary" style="font-size: 10px;">{{ $room->room_type }}</span>
                                        </div>

                                        <p class="text-muted small mb-2 text-truncate-2" style="font-size: 12px;">
                                            {{ $room->description ?? 'Kamar nyaman, bersih, dan siap huni.' }}
                                        </p>

                                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                            <span class="text-success fw-bold small">
                                                Rp {{ number_format($room->price ?? $room->monthly_price ?? 0, 0, ',', '.') }}<span class="text-muted fw-normal" style="font-size: 11px;">/bln</span>
                                            </span>
                                            
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="radio" name="room_id" id="room_{{ $room->id }}" value="{{ $room->id }}" required>
                                                <label class="form-check-label fw-bold small text-primary ms-1" for="room_{{ $room->id }}" style="cursor: pointer;">
                                                    Pilih
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning text-center small mb-0">
                                    <i class="bi bi-exclamation-circle me-1"></i> Mohon maaf, saat ini belum ada kamar yang kosong/tersedia.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label small fw-bold">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label small fw-bold">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mb-3 mt-2">Daftar Sekarang</button>

                <div class="text-center small">
                    <span class="text-muted">Sudah punya akun?</span> 
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login di sini</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>