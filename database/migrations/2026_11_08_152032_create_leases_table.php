<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Null jika kontrak belum ditentukan batas akhirnya
            $table->decimal('monthly_price', 12, 2);
            $table->decimal('deposit', 12, 2)->default(0);
            $table->string('status', 30)->default('active'); // active, completed, terminated
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('leases');
    }
};
