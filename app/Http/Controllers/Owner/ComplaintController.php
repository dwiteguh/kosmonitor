<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintUpdate;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Menampilkan daftar semua keluhan dari penghuni.
     */
    public function index()
    {
        // Ambil data keluhan beserta relasi tenant dan room
        $complaints = Complaint::with(['tenant.user', 'room'])->latest()->get();
        return view('owner.complaints.index', compact('complaints'));
    }

    /**
     * Menampilkan detail satu keluhan beserta riwayat tanggapan.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['tenant.user', 'room', 'updates.user']);
        return view('owner.complaints.show', compact('complaint'));
    }

    /**
     * Memperbarui status penanganan keluhan dan menambahkan catatan tanggapan.
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,rejected',
            'message' => 'required|string', // Catatan tanggapan dari pemilik
        ]);

        // 1. Update status keluhan utama
        $complaint->update([
            'status' => $validated['status']
        ]);

        // 2. Simpan riwayat update/tanggapan ke tabel complaint_updates
        ComplaintUpdate::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(), // ID pemilik yang sedang login
            'status' => $validated['status'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('owner.complaints.show', $complaint->id)
            ->with('success', 'Status keluhan berhasil diperbarui dan tanggapan telah dikirim.');
    }
}
