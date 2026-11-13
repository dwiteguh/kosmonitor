<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100); // Listrik, Air, Kebersihan, Perbaikan, dll
            $table->text('description');
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->string('receipt_path', 255)->nullable(); // Path foto bukti pengeluaran
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('expenses');
    }
};
