<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTable extends Migration
{
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id('id_booking');
            $table->string('nama_pelanggan'); // Nama diketik sendiri
            $table->unsignedBigInteger('photographer_id');
            $table->unsignedBigInteger('package_id');
            $table->date('tanggal_booking');
            $table->text('alamat');
            $table->string('status', 20)->default('Pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking');
    }
}