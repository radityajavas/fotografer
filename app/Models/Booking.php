<?php

namespace App\Models;

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
}