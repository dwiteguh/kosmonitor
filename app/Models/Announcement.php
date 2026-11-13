<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {
    protected $fillable = [
        'created_by', 'title', 'content', 'published_at'
    ];

    public function author() {
        return $this->belongsTo(User::class, 'created_by');
    }
}