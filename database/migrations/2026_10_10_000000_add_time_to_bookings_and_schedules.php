<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Booking: jam mulai & selesai (NULL = booking lama / seharian penuh)
        Schema::table('bookings', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('booking_date');
            $table->time('end_time')->nullable()->after('start_time');
        });

        // Jadwal libur: jam mulai & selesai (NULL = libur seharian)
        Schema::table('schedules', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('date');
            $table->time('end_time')->nullable()->after('start_time');

            // Index biasa untuk foreign key, supaya unique lama bisa dilepas
            $table->index('photographer_id', 'schedules_photographer_id_index');
        });

        // Satu fotografer boleh punya beberapa blok jam libur di tanggal yang sama
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropUnique(['photographer_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unique(['photographer_id', 'date']);
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('schedules_photographer_id_index');
            $table->dropColumn(['start_time', 'end_time']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
