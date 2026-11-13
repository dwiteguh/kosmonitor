<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    protected $fillable = [
        'lease_id', 'invoice_number', 'billing_period', 'amount', 'due_date', 'status'
    ];

    public function lease() {
        return $this->belongsTo(Lease::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
