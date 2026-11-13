<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('complaint_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained('complaints')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Bisa pemilik atau penghuni yang membalas
            $table->text('message');
            $table->string('status', 30); // Status keluhan pada saat update ini dibuat
            $table->timestamp('created_at')->useCurrent(); // Hanya butuh waktu dibuat
        });
    }

    public function down(): void {
        Schema::dropIfExists('complaint_updates');
    }
};
