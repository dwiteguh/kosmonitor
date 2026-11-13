<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    public $timestamps = false; // Hanya menggunakan created_at

    protected $fillable = [
        'user_id', 'activity', 'description', 'created_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}