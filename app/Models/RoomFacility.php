<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

// Menggunakan Pivot, bukan Model biasa
class RoomFacility extends Pivot {
    protected $table = 'room_facilities';
}
