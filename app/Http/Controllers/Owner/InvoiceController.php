<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Lease;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Menampilkan daftar seluruh tagihan.
     */
    public function index()
    {
        $invoices = Invoice::with(['lease.tenant.user', 'lease.room'])->latest()->get();
        return view('owner.invoices.index', compact('invoices'));
    }

    /**
     * Menampilkan form untuk membuat tagihan baru.
     */
    public function create()
    {
        // Ambil hanya kontrak penyewaan yang berstatus 'active'
        $activeLeases = Lease::where('status', 'active')->with(['tenant.user', 'room'])->get();
        
        return view('owner.invoices.create', compact('activeLeases'));
    }

    /**
     * Menyimpan tagihan bulanan ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'billing_period' => 'required|date', // Format tanggal mewakili bulan (misal: 2026-11-01)
            'due_date' => 'required|date|after_or_equal:billing_period',
            'amount' => 'required|numeric|min:0',
        ]);

        // Normalisasi tanggal billing_period ke tanggal 1 awal bulan (untuk konsistensi pencarian)
        $billingPeriod = Carbon::parse($validated['billing_period'])->startOfMonth()->format('Y-m-d');

        // Cek apakah tagihan untuk lease dan periode bulan ini sudah pernah dibuat sebelumnya
        $existingInvoice = Invoice::where('lease_id', $validated['lease_id'])
            ->whereDate('billing_period', $billingPeriod)
            ->first();

        if ($existingInvoice) {
            return back()->withInput()->withErrors([
                'billing_period' => 'Tagihan untuk periode bulan ini pada kontrak tersebut sudah pernah dibuat.'
            ]);
        }

        // Generate Nomor Tagihan Unik: INV-YYYYMM-RAND
        $invoiceNumber = 'INV-' . date('Ymd', strtotime($billingPeriod)) . '-' . strtoupper(Str::random(4));

        Invoice::create([
            'lease_id' => $validated['lease_id'],
            'invoice_number' => $invoiceNumber,
            'billing_period' => $billingPeriod,
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
            'status' => 'unpaid', // Status awal belum dibayar
        ]);

        return redirect()->route('owner.invoices.index')
            ->with('success', 'Tagihan bulanan berhasil dibuat.');
    }

    /**
     * Menampilkan detail tagihan dan riwayat pembayarannya.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['lease.tenant.user', 'lease.room', 'payments.verifier']);
        return view('owner.invoices.show', compact('invoice'));
    }

    /**
     * Menghapus tagihan (jika salah input).
     */
    public function destroy(Invoice $invoice)
    {
        // Pastikan hanya tagihan unpaid yang bisa dihapus sembarangan
        if ($invoice->status == 'paid') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat dihapus.');
        }

        $invoice->delete();

        return redirect()->route('owner.invoices.index')
            ->with('success', 'Data tagihan berhasil dihapus.');
    }
}