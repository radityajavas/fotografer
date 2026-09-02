<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'nama_pelanggan',
        'photographer_id',
        'package_id',
        'tanggal_booking',
        'alamat',
        'status',
    ];

    public function photographer()
    {
        return $this->belongsTo(\App\Models\Photographer::class, 'photographer_id');
    }

    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id');
    }
}