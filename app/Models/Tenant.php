<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model {
    protected $fillable = [
        'user_id', 'tenant_code', 'address', 'emergency_contact', 'identity_number'
    ];

    // Relasi balik ke user
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke tabel leases (akan dibuat di tahap berikutnya)
    public function leases() {
        return $this->hasMany(Lease::class);
    }
}
