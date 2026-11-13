<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('boarding_house_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('address');
            $table->string('phone', 20);
            $table->string('whatsapp', 20);
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_name', 100)->nullable();
            $table->string('bank_account_number', 50)->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('boarding_house_settings');
    }
};
