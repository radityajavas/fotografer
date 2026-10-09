<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Kode booking menyimpan alamat di kolom `lokasi`. Migrasi ini hanya
    // menambahkan kolom itu jika belum ada, jadi aman dijalankan di database mana pun.
    public function up(): void
    {
        if (Schema::hasTable('bookings') && ! Schema::hasColumn('bookings', 'lokasi')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->text('lokasi')->nullable();
            });
        }
    }

    public function down(): void
    {
        // sengaja kosong: kolom mungkin sudah ada sebelum migrasi ini
    }
};
