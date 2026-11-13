<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lease;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class LeaseController extends Controller
{
    /**
     * Menampilkan daftar penyewaan aktif maupun riwayat.
     */
    public function index()
    {
        // Ambil data lease beserta relasi tenant (dan user-nya) serta room
        $leases = Lease::with(['tenant.user', 'room'])->latest()->get();
        return view('owner.leases.index', compact('leases'));
    }

    /**
     * Menampilkan form untuk membuat kontrak penyewaan baru.
     */
    public function create()
    {
        // Hanya tampilkan kamar yang statusnya 'available' (kosong)
        $rooms = Room::where('status', 'available')->get();
        
        // Ambil daftar penghuni untuk dipilih
        $tenants = Tenant::with('user')->get();

        return view('owner.leases.create', compact('rooms', 'tenants'));
    }

    /**
     * Menyimpan data penyewaan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'monthly_price' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // 1. Buat data penyewaan (status default 'active')
                $lease = Lease::create([
                    'tenant_id' => $validated['tenant_id'],
                    'room_id' => $validated['room_id'],
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'monthly_price' => $validated['monthly_price'],
                    'deposit' => $validated['deposit'] ?? 0,
                    'status' => 'active',
                ]);

                // 2. Ubah status kamar yang dipilih menjadi 'occupied' (terisi)
                Room::where('id', $validated['room_id'])->update([
                    'status' => 'occupied'
                ]);
            });

            return redirect()->route('owner.leases.index')
                ->with('success', 'Kontrak penyewaan berhasil dibuat dan kamar otomatis berstatus terisi.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan penyewaan: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan detail satu kontrak penyewaan.
     */
    public function show(Lease $lease)
    {
        $lease->load(['tenant.user', 'room', 'invoices']);
        return view('owner.leases.show', compact('lease'));
    }

    /**
     * Mengakhiri/Menutup masa penyewaan (Checkout).
     */
    public function destroy(Lease $lease)
    {
        try {
            DB::transaction(function () use ($lease) {
                // 1. Ubah status lease menjadi 'terminated' atau 'completed'
                $lease->update(['status' => 'completed']);

                // 2. Kembalikan status kamar menjadi 'available' (kosong)
                Room::where('id', $lease->room_id)->update([
                    'status' => 'available'
                ]);
            });

            return redirect()->route('owner.leases.index')
                ->with('success', 'Masa penyewaan telah diakhiri dan kamar kembali tersedia.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengakhiri penyewaan: ' . $e->getMessage());
        }
    }

    public function approve(Lease $lease)
{
    DB::transaction(function () use ($lease) {
        // 1. Ubah status lease menjadi active
        $lease->update(['status' => 'active']);

        // 2. Ubah status kamar menjadi occupied (terisi)
        $lease->room->update(['status' => 'occupied']);
    });

    return back()->with('success', 'Kontrak sewa berhasil disetujui dan kamar resmi terisi.');
}
}