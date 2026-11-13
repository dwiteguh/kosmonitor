<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'phone',
         'is_active',
         'role',         // <-- Add this
        'phone',        // <-- Add this
        'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relasi: Satu user (jika role tenant) memiliki satu data profil penghuni
    public function tenantProfile() {
        return $this->hasOne(Tenant::class, 'user_id');
    }
}
