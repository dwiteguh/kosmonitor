<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {
    public function up(): void {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('title', 150);
            $table->text('description');
            $table->string('image', 255)->nullable(); // Foto kerusakan/masalah
            $table->string('status', 30)->default('waiting'); // waiting, processing, completed, rejected
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('complaints');
    }
};
