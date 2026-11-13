<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingHouseSetting extends Model
{
    // Nonaktifkan pencatatan waktu otomatis untuk tabel ini
    public $timestamps = false;

    // Izinkan pengisian data melalui Seeder
    protected $fillable = [
        'name', 
        'address', 
        'phone', 
        'whatsapp', 
        'bank_name', 
        'bank_account_name', 
        'bank_account_number'
    ];
}