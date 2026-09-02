<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat',
    ];

    // Relasi: 1 Pelanggan punya banyak Booking
    public function booking()
    {
        return $this->hasMany(Booking::class, 'id_pelanggan');
    }
}