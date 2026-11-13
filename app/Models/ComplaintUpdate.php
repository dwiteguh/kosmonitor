<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintUpdate extends Model {
    // Karena kita tidak memakai kolom updated_at di migration
    public $timestamps = false; 

    protected $fillable = [
        'complaint_id', 'user_id', 'message', 'status', 'created_at'
    ];

    public function complaint() {
        return $this->belongsTo(Complaint::class);
    }

    // Relasi ke User (bisa untuk menampilkan siapa yang membalas: nama dan role)
    public function user() {
        return $this->belongsTo(User::class);
    }
}