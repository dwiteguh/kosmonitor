<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {
    protected $fillable = [
        'room_number', 'room_type', 'monthly_price', 'description', 'photo', 'status'
    ];

    // Relasi ke tabel leases
    public function leases() {
        return $this->hasMany(Lease::class);
    }

    public function facilities() {
        return $this->belongsToMany(Facility::class, 'room_facilities')
                    ->withTimestamps();
    }
}