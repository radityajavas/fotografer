<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Model User (Pemesan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Model Photographer
    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    // Relasi ke Model Package
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}