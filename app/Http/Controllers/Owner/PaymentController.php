<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function verify(Payment $payment)
    {
        // 1. Ubah status pembayaran menjadi verified (disetujui)
        $payment->update([
            'status' => 'verified'
        ]);

        // 2. Otomatis ubah status tagihan (invoice) terkait menjadi 'paid' (lunas)
        $payment->invoice->update([
            'status' => 'paid'
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi dan tagihan dinyatakan lunas.');
    }
}
