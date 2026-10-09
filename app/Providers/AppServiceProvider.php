<?php

namespace App\Providers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pesan validasi berbahasa Indonesia (berlaku untuk seluruh form)
        $locale = app()->getLocale();

        Lang::addLines([
            'validation.required'        => ':attribute wajib diisi.',
            'validation.string'          => ':attribute harus berupa teks.',
            'validation.email'           => 'Format :attribute tidak valid.',
            'validation.unique'          => ':attribute sudah terdaftar.',
            'validation.confirmed'       => 'Konfirmasi :attribute tidak cocok.',
            'validation.regex'           => 'Format :attribute tidak valid.',
            'validation.numeric'         => ':attribute harus berupa angka.',
            'validation.integer'         => ':attribute harus berupa bilangan bulat.',
            'validation.date'            => ':attribute bukan tanggal yang valid.',
            'validation.exists'          => ':attribute yang dipilih tidak valid.',
            'validation.in'              => ':attribute yang dipilih tidak valid.',
            'validation.after_or_equal'  => ':attribute tidak boleh sebelum :date.',
            'validation.min.string'      => ':attribute minimal :min karakter.',
            'validation.max.string'      => ':attribute maksimal :max karakter.',
            'validation.min.numeric'     => ':attribute minimal :min.',
            'validation.max.numeric'     => ':attribute maksimal :max.',
            'validation.between.numeric' => ':attribute harus di antara :min dan :max.',
            'validation.image'           => ':attribute harus berupa gambar.',
            'validation.mimes'           => ':attribute harus berformat :values.',
            'validation.password.letters' => ':attribute harus mengandung huruf.',
            'validation.password.numbers' => ':attribute harus mengandung angka.',
            'validation.password.min'     => ':attribute minimal :min karakter.',
            'validation.attributes.name'            => 'Nama',
            'validation.attributes.email'           => 'Email',
            'validation.attributes.phone'           => 'Nomor telepon',
            'validation.attributes.address'         => 'Alamat',
            'validation.attributes.password'        => 'Kata sandi',
            'validation.attributes.city'            => 'Kota',
            'validation.attributes.specialization'  => 'Spesialisasi',
            'validation.attributes.status'          => 'Status',
            'validation.attributes.price'           => 'Harga',
            'validation.attributes.duration_hours'  => 'Durasi',
            'validation.attributes.description'     => 'Deskripsi',
            'validation.attributes.booking_date'    => 'Tanggal pelaksanaan',
            'validation.attributes.package_id'      => 'Paket',
            'validation.attributes.photographer_id' => 'Fotografer',
            'validation.attributes.kota'            => 'Kota pemotretan',
            'validation.attributes.lokasi'          => 'Alamat lokasi',
            'validation.attributes.message'         => 'Pesan',
            'validation.attributes.date'            => 'Tanggal',
            'validation.attributes.photo'           => 'Foto',
        ], $locale);
    }
}
