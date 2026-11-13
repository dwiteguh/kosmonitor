<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Room;
use App\Models\Lease;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TenantRegisterController extends Controller
{
    // MENGIRIM DATA KE HALAMAN LOGIN & REGISTER (SPLIT-SCREEN)
    public function create()
    {
        $availableRooms = Room::where('status', 'available')->get();
        return view('auth.login', compact('availableRooms'));
    }
    
    // PROSES REGISTRASI KEDUANYA (OWNER & TENANT)
    public function store(Request $request)
    {
        $isOwner = $request->role_register === 'owner';

        // 1. Aturan Validasi Dasar
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'role_register' => 'required|in:tenant,owner'
        ];

        // Jika yang daftar adalah tenant, wajib pilih kamar
        if (!$isOwner) {
            $rules['room_id'] = 'required|exists:rooms,id';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $isOwner) {
            // 2. Buat Akun User sesuai Role
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $isOwner ? 'owner' : 'tenant',
            ]);

            // 3. Jika yang daftar adalah Penghuni (Tenant), buatkan profil dan kontrak
            if (!$isOwner) {
                $tenantCode = 'TNT-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

                $tenant = Tenant::create([
                    'user_id' => $user->id,
                    'tenant_code' => $tenantCode,
                    'phone' => $validated['phone'],
                    'identity_number' => '-', 
                    'address' => '-',
                ]);

                $room = Room::findOrFail($validated['room_id']);

                Lease::create([
                    'room_id' => $room->id,
                    'tenant_id' => $tenant->id,
                    'start_date' => now(),
                    'monthly_price' => $room->price ?? $room->monthly_price ?? 0, 
                    'status' => 'pending',
                ]);
            }

            // Login otomatis setelah sukses daftar
            Auth::login($user);
        });

        // 4. Arahkan ke dashboard masing-masing setelah sukses daftar
        if ($isOwner) {
            return redirect()->route('owner.dashboard')
                ->with('success', 'Akun Pemilik Kos berhasil dibuat!');
        }

        return redirect()->route('tenant.dashboard')
            ->with('success', 'Registrasi berhasil! Permintaan kamar Anda sedang menunggu persetujuan pemilik kos.');
    }

    // PROSES LOGIN DENGAN VALIDASI ROLE (OWNER / TENANT)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role_login' => 'required|in:tenant,owner' 
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();

            if ($user->role !== $request->role_login) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $roleName = $request->role_login == 'owner' ? 'Pemilik Kos' : 'Penghuni';
                
                return back()->withErrors([
                    'email' => "Akses ditolak! Anda mencoba masuk sebagai {$roleName}, tetapi Anda tidak terdaftar sebagai {$roleName}.",
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();

            if ($user->role === 'owner') {
                return redirect()->route('owner.dashboard');
            } 
            
            return redirect()->route('tenant.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email'));
    }
    
    // PROSES LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}