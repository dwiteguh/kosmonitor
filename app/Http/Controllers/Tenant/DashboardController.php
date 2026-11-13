<?php

namespace App\Http\Controllers\Tenant;

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\Invoice;
use App\Models\Complaint;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Ambil profil tenant berdasarkan user yang login
        $tenant = Tenant::where('user_id', $user->id)->first();

        $activeLease = null;
        $unpaidInvoicesCount = 0;
        $activeComplaintsCount = 0;

        if ($tenant) {
            // 2. Ambil kontrak aktif
            $activeLease = Lease::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->with(['room'])
                ->first();

            // 3. Ambil daftar ID kontrak milik tenant ini
            $leaseIds = Lease::where('tenant_id', $tenant->id)->pluck('id');

            // 4. Hitung jumlah tagihan yang belum lunas (status unpaid, partial, atau overdue)
            $unpaidInvoicesCount = Invoice::whereIn('lease_id', $leaseIds)
                ->whereIn('status', ['unpaid', 'partial', 'overdue'])
                ->count();

            // 5. Hitung jumlah keluhan yang masih aktif/diproses (status pending atau in_progress)
            $activeComplaintsCount = Complaint::where('tenant_id', $tenant->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->count();
        }

        return view('tenant.dashboard', compact('tenant', 'activeLease', 'unpaidInvoicesCount', 'activeComplaintsCount'));
    }
}