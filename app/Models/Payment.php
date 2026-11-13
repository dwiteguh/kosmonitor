<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = [
        'invoice_id', 'amount', 'payment_date', 'payment_method', 'proof_path', 'status', 'verified_by', 'verified_at', 'notes'
    ];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    // Relasi ke User (Pemilik) yang melakukan verifikasi
    public function verifier() {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
