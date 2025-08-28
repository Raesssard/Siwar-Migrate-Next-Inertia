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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            // Kolom ini akan membedakan antara pemasukan dan pengeluaran
            $table->string('rt');
            $table->date('tanggal');
            $table->string('pemasukan');
            $table->string('pengeluaran');
            $table->string('nama_transaksi'); // Nama atau deskripsi transaksi
            $table->decimal('jumlah', 15, 2); // Menggunakan decimal untuk uang
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran'); // Ini akan menghapus tabel 'pengeluaran'
    }
};