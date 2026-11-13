<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;
use App\Models\Tenant;
use App\Models\Lease;

class ComplaintController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();
        $complaints = collect();

        if ($tenant) {
            $complaints = Complaint::where('tenant_id', $tenant->id)->latest()->get();
        }

        return view('tenant.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();
        
        $activeLease = null;
        if ($tenant) {
            $activeLease = Lease::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->with('room')
                ->first();
        }

        return view('tenant.complaints.create', compact('activeLease'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();

        if (!$tenant) {
            return back()->with('error', 'Profil penghuni tidak ditemukan untuk akun ini.');
        }

        $activeLease = Lease::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->first();

        if (!$activeLease) {
            return back()->with('error', 'Anda tidak memiliki kontrak kamar aktif untuk mengajukan keluhan.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('complaints', 'public');
        }

        Complaint::create([
            'tenant_id' => $tenant->id,
            'room_id' => $activeLease->room_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('tenant.complaints.index')
            ->with('success', 'Keluhan berhasil dikirim dan akan segera ditinjau oleh pemilik kos.');
    }

    public function show(Complaint $complaint)
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();

        if (!$tenant || $complaint->tenant_id != $tenant->id) {
            abort(403, 'Unauthorized action.');
        }

        $complaint->load(['room', 'updates.user']);
        return view('tenant.complaints.show', compact('complaint'));
    }
}