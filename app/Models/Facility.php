<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model {
    protected $fillable = [
        'name', 'description'
    ];

    // Relasi Many-to-Many melalui tabel pivot room_facilities
    public function rooms() {
        return $this->belongsToMany(Room::class, 'room_facilities')
                    ->withTimestamps();
    }
}