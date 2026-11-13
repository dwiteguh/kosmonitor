<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['user', 'leases.room'])->latest()->get();
        return view('owner.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['user', 'leases.room']);

        
        return view('owner.tenants.show', compact('tenant'));
    }

    // Hapus atau abaikan fungsi edit & update agar Owner TIDAK BISA edit data penghuni
    
    public function destroy(Tenant $tenant)
    {
        // Hapus user terkait jika perlu
        $tenant->user()->delete();
        $tenant->delete();

        return redirect()->route('owner.tenants.index')
            ->with('success', 'Data penghuni berhasil dihapus.');
    }
}