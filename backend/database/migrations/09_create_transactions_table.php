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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('members');
            $table->foreignId('id_loan')->nullable()->constrained('loans');
            $table->foreignId('id_installment')->nullable()->constrained('installments');
            $table->enum('tipe_transaksi', ['debit', 'kredit']);
            $table->string('tgl_transaksi');
            $table->decimal('jumlah', 15, 2);
            $table->foreignId('id_deposit')->nullable()->constrained('deposits');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
