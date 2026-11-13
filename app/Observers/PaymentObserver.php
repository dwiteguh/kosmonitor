<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Invoice;

class PaymentObserver
{
    /**
     * Handle the Payment "saved" event.
     * Memicu ketika payment baru dibuat atau di-update.
     */
    public function saved(Payment $payment): void
    {
        // Hanya lakukan pengecekan jika status pembayaran diverifikasi
        // atau jika statusnya baru saja berubah dari verified ke status lain
        $this->updateInvoiceStatus($payment->invoice_id);
    }

    /**
     * Handle the Payment "deleted" event.
     * Memicu ketika payment dihapus, untuk jaga-jaga jika admin membatalkan transaksi.
     */
    public function deleted(Payment $payment): void
    {
        $this->updateInvoiceStatus($payment->invoice_id);
    }

    /**
     * Fungsi private untuk menghitung dan memperbarui status Invoice
     */
    private function updateInvoiceStatus($invoiceId): void
    {
        $invoice = Invoice::find($invoiceId);
        
        if (!$invoice) return;

        // Hitung total semua pembayaran yang statusnya 'verified' untuk tagihan ini
        $totalVerifiedPayments = $invoice->payments()
            ->where('status', 'verified')
            ->sum('amount');

        // Tentukan status baru berdasarkan jumlah yang sudah dibayar
        if ($totalVerifiedPayments >= $invoice->amount) {
            $status = 'paid';
        } elseif ($totalVerifiedPayments > 0) {
            $status = 'partial'; // Dibayar sebagian
        } else {
            // Jika tidak ada pembayaran atau semua pembayaran direject/pending
            // Cek apakah sudah lewat jatuh tempo
            $status = now()->gt($invoice->due_date) ? 'overdue' : 'unpaid';
        }

        // Update status invoice jika ada perubahan
        if ($invoice->status !== $status) {
            $invoice->update(['status' => $status]);
        }
    }
}