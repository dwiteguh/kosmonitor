<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\Invoice;
use App\Models\Complaint;

class DashboardController extends Controller
{
   public function index()
{
    $totalRooms = Room::count();
    $activeTenants = Tenant::count();
    $emptyRooms = Room::where('status', 'available')->count();
    $occupiedRooms = Room::where('status', 'occupied')->count();
    $newComplaintsCount = Complaint::where('status', 'pending')->count();

    $activeTenants = \App\Models\Tenant::whereHas('leases', function($query) {
    $query->where('status', 'active');
})->count();
    
    $thisMonthIncome = Invoice::where('status', 'paid')
        ->whereMonth('billing_period', now()->month)
        ->sum('amount');
        
    $thisMonthExpense = 0; // Sesuaikan jika ada tabel pengeluaran
    $unpaidInvoices = Invoice::whereIn('status', ['unpaid', 'partial', 'overdue'])->count();

    return view('owner.dashboard', compact(
        'totalRooms',
        'activeTenants',
        'emptyRooms',
        'occupiedRooms',
        'newComplaintsCount',
        'thisMonthIncome',
        'thisMonthExpense',
        'unpaidInvoices'
    ));
}
}