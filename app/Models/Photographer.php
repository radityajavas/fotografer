<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photographer extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Tambahkan method relasi ini
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}