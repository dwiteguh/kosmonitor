<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('tenant_code', 30)->unique();
            $table->text('address');
            $table->string('emergency_contact', 100)->nullable();
            $table->string('identity_number', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tenants');
    }
};
