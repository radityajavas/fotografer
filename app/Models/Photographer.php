<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photographer extends Model
{
    protected $fillable = ['name', 'phone', 'specialization', 'status'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}