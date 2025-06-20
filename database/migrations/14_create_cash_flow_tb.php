<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cash_flow', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->decimal('nominal', 15, 2);
            $table->boolean('is_cash_in'); // true = kas masuk, false = kas keluar
            $table->enum('type', [
                'operational',      // biaya operasional
                'misc_income',      // pemasukan lain-lain
                'misc_expense',     // pengeluaran lain-lain
                'transfer',         // transfer antar akun
                'adjustment'        // koreksi saldo
            ]);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('created_by'); // id admin
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('related_transaction_id')->nullable(); // relasi opsional ke tabel transactions
            $table->foreign('related_transaction_id')->references('id')->on('transactions')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flow');
    }
};
