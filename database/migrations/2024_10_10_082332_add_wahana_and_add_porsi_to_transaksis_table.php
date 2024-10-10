<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambahkan kolom wahana dan porsi
            $table->integer('wahana')->nullable();
            $table->integer('porsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menghapus kolom saat rollback migration
            $table->dropColumn('wahana');
            $table->dropColumn('porsi');
        });
    }
};
