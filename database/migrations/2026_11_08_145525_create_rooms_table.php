<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number', 20)->unique();
            $table->string('room_type', 50);
            $table->decimal('monthly_price', 12, 2);
            $table->text('description')->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('status', 30)->default('available'); // available, occupied, maintenance
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rooms');
    }
};
