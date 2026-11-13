<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\BoardingHouseSetting;
use App\Models\Room;
use App\Models\Tenant;

class KosMonitorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Pemilik (Owner) Default
        User::create([
            'name' => 'Pemilik Kos',
            'email' => 'owner@kosmonitor.com',
            'password' => Hash::make('password123'), // Password default
            'role' => 'owner',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // 2. Buat Pengaturan Informasi Kos
        BoardingHouseSetting::create([
            'name' => 'KosMonitor Residence',
            'address' => 'Jl. Mawar No. 123, Jakarta Selatan',
            'phone' => '081234567890',
            'whatsapp' => '081234567890',
            'bank_name' => 'BCA',
            'bank_account_name' => 'Pemilik Kos',
            'bank_account_number' => '1234567890',
        ]);

        // 3. Buat Beberapa Data Kamar Dummy
        Room::create([
            'room_number' => 'A01',
            'room_type' => 'Standar',
            'monthly_price' => 1500000,
            'description' => 'Kamar standar dengan kipas angin dan kasur single.',
            'status' => 'available',
        ]);

        Room::create([
            'room_number' => 'A02',
            'room_type' => 'VIP',
            'monthly_price' => 2500000,
            'description' => 'Kamar VIP dengan AC, TV, dan kamar mandi dalam.',
            'status' => 'available',
        ]);

        // 4. Buat Satu Akun Penghuni (Tenant) Dummy untuk Testing
        $tenantUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'tenant',
            'phone' => '089876543210',
            'is_active' => true,
        ]);

        // Buat Profil Penghuninya
        Tenant::create([
            'user_id' => $tenantUser->id,
            'tenant_code' => 'TNT-' . date('Ymd') . '-001',
            'address' => 'Jl. Melati No. 45, Bandung',
            'emergency_contact' => '081122334455',
            'identity_number' => '3273000011112222',
        ]);
    }
}