<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Pemesan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Fotografer
    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    // Paket foto
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Contoh: "09:00–12:00" (null jika booking seharian / data lama)
    public function getTimeRangeAttribute(): ?string
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        return substr($this->start_time, 0, 5) . '–' . substr($this->end_time, 0, 5);
    }

    /**
     * Filter booking yang jamnya bertabrakan dengan rentang yang diminta.
     * Aturan: A bentrok dengan B jika mulaiA < selesaiB dan selesaiA > mulaiB.
     * Booking tanpa jam (data lama) dianggap memakai seharian penuh.
     */
    public function scopeOverlap($query, $date, $start = null, $end = null)
    {
        $query->whereDate('booking_date', $date);

        if (!$start || !$end) {
            return $query; // yang diminta seharian: semua booking di tanggal itu bentrok
        }

        return $query->where(function ($w) use ($start, $end) {
            $w->whereNull('start_time')
              ->orWhereNull('end_time')
              ->orWhere(fn ($x) => $x->where('start_time', '<', $end)->where('end_time', '>', $start));
        });
    }

    // Apakah booking ini bentrok dengan booking lain (tanggal & jam)?
    public function overlapsWith(Booking $other): bool
    {
        if (Carbon::parse($this->booking_date)->toDateString() !== Carbon::parse($other->booking_date)->toDateString()) {
            return false;
        }

        if (!$this->start_time || !$this->end_time || !$other->start_time || !$other->end_time) {
            return true;
        }

        return $this->start_time < $other->end_time && $this->end_time > $other->start_time;
    }
}
