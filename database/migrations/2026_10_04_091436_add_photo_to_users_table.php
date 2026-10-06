<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Menambahkan kolom "photo" untuk menyimpan nama/path foto profil
            // nullable() = boleh kosong kalau pelanggan belum upload foto
            $table->string('photo')->nullable()->after('address');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Kalau migration dibatalkan, kolom "photo" ikut dihapus
            $table->dropColumn('photo');

        });
    }
};