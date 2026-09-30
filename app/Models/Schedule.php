<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['photographer_id', 'date', 'note'];

    protected $casts = [
        'date' => 'date',
    ];

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }
}