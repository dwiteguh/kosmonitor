<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk & Daftar - KosMonitor</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        * { box-sizing: border-box; }
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh; margin: 0; padding: 20px;
        }

        /* Container Utama */
        .auth-container {
            background-color: #fff; border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.08);
            position: relative; overflow: hidden; width: 950px; max-width: 100%; min-height: 600px;
        }

        .form-container {
            position: absolute; top: 0; height: 100%; transition: all 0.6s ease-in-out;
            display: flex; align-items: center; justify-content: center; padding: 30px 40px;
        }

        /* Form Login (Kiri) */
        .sign-in-container {
            left: 0; width: 50%; z-index: 2; pointer-events: auto; 
        }

        .auth-container.right-panel-active .sign-in-container {
            transform: translateX(100%); opacity: 0; z-index: 1; pointer-events: none; 
        }

        /* Form Register (Kanan) */
        .sign-up-container {
            left: 0; width: 50%; opacity: 0; z-index: 1; pointer-events: none; 
        }

        .auth-container.right-panel-active .sign-up-container {
            transform: translateX(100%); opacity: 1; z-index: 5; animation: show 0.6s; pointer-events: auto; 
        }

        @keyframes show {
            0%, 49% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        /* Panel Geser (Overlay Biru) */
        .overlay-container {
            position: absolute; top: 0; left: 50%; width: 50%; height: 100%;
            overflow: hidden; transition: transform 0.6s ease-in-out; z-index: 100;
        }

        .auth-container.right-panel-active .overlay-container { transform: translateX(-100%); }

        .overlay {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: #fff; position: relative;
            left: -100%; height: 100%; width: 200%; transform: translateX(0); transition: transform 0.6s ease-in-out;
        }

        .auth-container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute; display: flex; align-items: center; justify-content: center;
            flex-direction: column; padding: 0 40px; text-align: center; top: 0; height: 100%; width: 50%;
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left { left: 0; transform: translateX(0); }
        .overlay-right { right: 0; transform: translateX(0); }

        /* Scrollbar & Box Form */
        .form-box { width: 100%; max-height: 540px; overflow-y: auto; padding-right: 5px; }
        .form-box::-webkit-scrollbar { width: 4px; }
        .form-box::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .room-card { cursor: pointer; transition: all 0.2s; }
        .room-card:hover { border-color: #0d6efd !important; background-color: #f8fafc; }
    </style>
</head>
<body>

    <div class="auth-container {{ $errors->has('name') || old('register_attempt') ? 'right-panel-active' : '' }}" id="authContainer">
        
        <!-- FORM REGISTER (KANAN) -->
        <div class="form-container sign-up-container">
            <div class="form-box">
                <div class="text-center mb-3">
                    <h4 class="fw-bold text-primary mb-1">Buat Akun</h4>
                    <p class="text-muted small">Daftarkan diri Anda di KosMonitor</p>
                </div>

                @if($errors->any() && old('register_attempt'))
                    <div class="alert alert-danger p-2 small mb-2">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="register_attempt" value="1">
                    
                    <!-- OPSI DAFTAR SEBAGAI -->
                    <div class="mb-2">
                        <label class="form-label small fw-bold mb-1">Daftar Sebagai</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="role_register" id="reg_role_tenant" value="tenant" checked onchange="toggleRoomSelection()">
                            <label class="btn btn-outline-primary btn-sm w-50 rounded-pill fw-bold" for="reg_role_tenant">
                                <i class="bi bi-person me-1"></i> Penghuni
                            </label>

                            <input type="radio" class="btn-check" name="role_register" id="reg_role_owner" value="owner" onchange="toggleRoomSelection()">
                            <label class="btn btn-outline-primary btn-sm w-50 rounded-pill fw-bold" for="reg_role_owner">
                                <i class="bi bi-person-badge me-1"></i> Pemilik Kos
                            </label>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold mb-1">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-sm" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold mb-1">Email</label>
                        <input type="email" class="form-control form-control-sm" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold mb-1">Nomor WhatsApp</label>
                        <input type="text" class="form-control form-control-sm" name="phone" value="{{ old('phone') }}" required>
                    </div>

                    <!-- Pilihan Kamar Interaktif (AKAN HILANG JIKA PILIH OWNER) -->
                    <div class="mb-2" id="roomSelectionSection">
                        <label class="form-label small fw-bold mb-1 text-primary"><i class="bi bi-door-open me-1"></i> Pilih Kamar Tersedia</label>
                        
                        @php
                            $availableRooms = \App\Models\Room::where('status', 'available')->get();
                        @endphp
                        
                        <div class="row g-2" style="max-height: 140px; overflow-y: auto;">
                            @forelse($availableRooms as $room)
                                <div class="col-12">
                                    <label class="card border p-2 room-card d-flex flex-row align-items-center justify-content-between mb-0" for="room_{{ $room->id }}" style="cursor: pointer;">
                                        <div>
                                            <span class="fw-bold small">Kamar {{ $room->room_number }}</span>
                                            <span class="badge bg-secondary ms-1" style="font-size: 10px;">{{ $room->room_type }}</span>
                                            <div class="text-success small fw-bold">Rp {{ number_format($room->price ?? $room->monthly_price ?? 0, 0, ',', '.') }}/bln</div>
                                        </div>
                                        <div class="form-check m-0">
                                            <input class="form-check-input room-radio" type="radio" name="room_id" id="room_{{ $room->id }}" value="{{ $room->id }}" required style="transform: scale(1.1); cursor: pointer;">
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted small py-2 border rounded bg-light">Belum ada kamar kosong.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 mb-2">
                            <label class="form-label small fw-bold mb-1">Password</label>
                            <input type="password" class="form-control form-control-sm" name="password" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label small fw-bold mb-1">Konfirmasi</label>
                            <input type="password" class="form-control form-control-sm" name="password_confirmation" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 py-2 mt-2 fw-bold rounded-pill">Daftar Sekarang</button>
                </form>
            </div>
        </div>

        <!-- FORM LOGIN (KIRI) -->
        <div class="form-container sign-in-container">
            <div class="form-box">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary mb-1">KosMonitor</h3>
                    <p class="text-muted small">Sistem Manajemen Kos Berbasis Web</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success p-2 small mb-3">{{ session('success') }}</div>
                @endif

                @if($errors->any() && !old('register_attempt'))
                    <div class="alert alert-danger p-2 small mb-3">Login gagal, periksa email, password, atau pilihan peran.</div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold mb-2">Masuk Sebagai</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="role_login" id="role_tenant" value="tenant" checked>
                            <label class="btn btn-outline-primary btn-sm w-50 rounded-pill fw-bold" for="role_tenant">
                                <i class="bi bi-person me-1"></i> Penghuni
                            </label>

                            <input type="radio" class="btn-check" name="role_login" id="role_owner" value="owner">
                            <label class="btn btn-outline-primary btn-sm w-50 rounded-pill fw-bold" for="role_owner">
                                <i class="bi bi-person-badge me-1"></i> Pemilik Kos
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" class="form-control" name="password" required placeholder="Masukkan password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3 rounded-pill">Masuk Sekarang</button>
                </form>
            </div>
        </div>

        <!-- PANEL GESER / OVERLAY -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-right">
                    <h2 class="fw-bold mb-3">Halo, Pengguna Baru!</h2>
                    <p class="mb-4 small lh-sm">Belum punya akun? Daftarkan diri Anda sebagai Penghuni atau Pemilik di KosMonitor.</p>
                    <button class="btn btn-outline-light px-4 py-2 rounded-pill fw-bold" id="signUpButton">Daftar</button>
                </div>
                <div class="overlay-panel overlay-left">
                    <h2 class="fw-bold mb-3">Selamat Datang Kembali!</h2>
                    <p class="mb-4 small lh-sm">Sudah memiliki akun? Silakan masuk untuk mengelola aktivitas kos Anda.</p>
                    <button class="btn btn-outline-light px-4 py-2 rounded-pill fw-bold" id="signInButton">Masuk</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Animasi Geser & Sembunyikan Kamar -->
    <script>
        // Animasi Geser Panel
        const signUpButton = document.getElementById('signUpButton');
        const signInButton = document.getElementById('signInButton');
        const authContainer = document.getElementById('authContainer');

        signUpButton.addEventListener('click', () => {
            authContainer.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            authContainer.classList.remove("right-panel-active");
        });

        // Script Sembunyikan Pilihan Kamar Jika Pilih Owner
        function toggleRoomSelection() {
            const isOwner = document.getElementById('reg_role_owner').checked;
            const roomSection = document.getElementById('roomSelectionSection');
            const roomRadios = document.querySelectorAll('.room-radio');

            if (isOwner) {
                // Sembunyikan bagian kamar dan matikan attribute required
                roomSection.style.display = 'none';
                roomRadios.forEach(radio => radio.required = false);
            } else {
                // Tampilkan kembali bagian kamar dan hidupkan required
                roomSection.style.display = 'block';
                roomRadios.forEach(radio => radio.required = true);
            }
        }
        
        // Panggil fungsi saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', toggleRoomSelection);
    </script>
</body>
</html>