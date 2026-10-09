<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photographer extends Model
{
    protected $fillable = ['name', 'phone', 'city', 'specialization', 'status'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Daftar kota yang dijangkau fotografer.
     * Kolom `city` boleh berisi beberapa kota dipisah koma, mis. "Malang, Batu".
     */
    public function serviceAreas(): array
    {
        return collect(explode(',', (string) $this->city))
            ->map(fn ($c) => trim($c))
            ->filter()
            ->unique(fn ($c) => mb_strtolower($c))
            ->values()
            ->all();
    }
}
