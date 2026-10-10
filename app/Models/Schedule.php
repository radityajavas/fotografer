<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['photographer_id', 'date', 'start_time', 'end_time', 'note'];

    protected $casts = [
        'date' => 'date',
    ];

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    // Contoh: "09:00–12:00" (null = libur seharian)
    public function getTimeRangeAttribute(): ?string
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        return substr($this->start_time, 0, 5) . '–' . substr($this->end_time, 0, 5);
    }

    // Filter jadwal libur yang bertabrakan dengan tanggal & jam yang diminta
    public function scopeOverlap($query, $date, $start = null, $end = null)
    {
        $query->whereDate('date', $date);

        if (!$start || !$end) {
            return $query;
        }

        return $query->where(function ($w) use ($start, $end) {
            $w->whereNull('start_time')
              ->orWhereNull('end_time')
              ->orWhere(fn ($x) => $x->where('start_time', '<', $end)->where('end_time', '>', $start));
        });
    }
}
