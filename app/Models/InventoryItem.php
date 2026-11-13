<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model {
    protected $fillable = [
        'room_id', 'name', 'quantity', 'condition', 'notes'
    ];

    public function room() {
        return $this->belongsTo(Room::class);
    }
}