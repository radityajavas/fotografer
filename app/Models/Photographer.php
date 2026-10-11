<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function photos()
    {
        return $this->hasMany(PhotographerPhoto::class);
    }

    protected static function booted()
    {
        static::deleting(function ($photographer) {
            foreach ($photographer->photos as $photo) {
                Storage::disk('public')->delete($photo->path);
            }
        });
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