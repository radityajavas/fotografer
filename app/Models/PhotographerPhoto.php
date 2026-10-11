<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotographerPhoto extends Model
{
    protected $fillable = ['photographer_id', 'path', 'kategori'];

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}