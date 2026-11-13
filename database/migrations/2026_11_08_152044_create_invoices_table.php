<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lease_id')->constrained('leases')->onDelete('cascade');
            $table->string('invoice_number', 50)->unique();
            $table->date('billing_period'); // Misal: 2026-11-01 (Mewakili tagihan bulan November 2026)
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->string('status', 30)->default('unpaid'); // unpaid, partial, paid, overdue, cancelled
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};