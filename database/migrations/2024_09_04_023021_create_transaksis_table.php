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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id(); // Ini sudah cukup untuk membuat kolom ID sebagai primary key
            $table->string('nm_konsumen');
            $table->string('nohp');
            $table->unsignedBigInteger('paket_id');
            $table->timestamps(); // Hanya perlu satu kali untuk created_at dan updated_at
        
            $table->foreign('paket_id')->references('id')->on('pakets')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
