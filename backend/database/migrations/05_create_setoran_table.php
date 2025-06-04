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
        Schema::create('setoran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_collector')->constrained('collectors');
            $table->date('tgl_setoran');
            $table->decimal('jml_setoran', 15, 2);
            $table->string('bukti_setoran');
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
