<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\Payment;

class InvoiceController extends Controller
{
public function index()
{
    $user = Auth::user();
    
    // 1. Ambil data tenant berdasarkan user_id yang sedang login
    $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();
    
    $invoices = collect();

    if ($tenant) {
        // 2. Ambil seluruh ID kontrak (lease) milik tenant ini
        $leaseIds = \App\Models\Lease::where('tenant_id', $tenant->id)->pluck('id');
        
        // 3. Ambil tagihan yang terikat pada kontrak-kontrak tersebut
        $invoices = \App\Models\Invoice::whereIn('lease_id', $leaseIds)->latest()->get();
    }

    return view('tenant.invoices.index', compact('invoices'));
}

    public function show(Invoice $invoice)
    {
        $user = Auth::user();
        $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();

        // Ambil daftar ID kontrak (lease) milik tenant yang sedang login
        $leaseIds = $tenant ? \App\Models\Lease::where('tenant_id', $tenant->id)->pluck('id') : collect();

        // Cek apakah tagihan ini termasuk dalam daftar kontrak milik tenant tersebut
        if (!$tenant || !in_array($invoice->lease_id, $leaseIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }

        $invoice->load(['lease.room', 'payments']);
        return view('tenant.invoices.show', compact('invoice'));
    }

    public function pay(Request $request, Invoice $invoice)
    {
        $user = Auth::user();
        $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();
        
        $leaseIds = $tenant ? \App\Models\Lease::where('tenant_id', $tenant->id)->pluck('id') : collect();

        if (!$tenant || !in_array($invoice->lease_id, $leaseIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('proof_of_payment')->store('payments', 'public');

        \App\Models\Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $validated['amount'],
            'payment_date' => now(),
            'payment_method' => $validated['payment_method'],
            'proof_of_payment' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('tenant.invoices.show', $invoice->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi pemilik.');
    }
}
